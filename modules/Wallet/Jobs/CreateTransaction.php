<?php

namespace Modules\Wallet\Jobs;

use App\Abstracts\Job;
use Illuminate\Support\Facades\DB;
use Modules\Wallet\Models\TransactionQueues;
use Modules\Wallet\Models\TransactionQueueRelation as TQRel;
use App\Models\Sale\Invoice;
use App\Models\Purchase\Bill;
use App\Models\Common\Company;
use App\Models\Setting\Category;
use App\Models\Banking\Account;
use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\CreateDocumentHistory;
use App\Jobs\Sale\CreateInvoice;
use App\Jobs\Sale\CreateInvoiceHistory;
use Modules\Wallet\Jobs\CreateWalletContact;
use Modules\Wallet\Jobs\FindAccount;
use Modules\Wallet\Jobs\ConvertCurrency;


class CreateTransaction extends Job
{
    protected $request;
    protected $transaction;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      session([ 'show_accounts' => true ]);
      DB::beginTransaction();
      try {
        //code...
        $data = $this->request->all();
        $transactionType = $data['type'];
        $from_company = Company::where('attribute_id', $data['from_attribution_id'])->first();
        $to_company = Company::where('attribute_id', $data['to_attribution_id'])->first();
        $from_account = null;
        if ($from_company)
        {
          $from_account = $this->dispatch(new FindAccount($from_company, $data['source'], $data['from_currency']));
          $data['from_account_id'] = $from_account->id;
        }
        $to_account = null;
        if ($to_company)
        {
          $to_account = $this->dispatch(new FindAccount($to_company, $data['source'], $data['to_currency']));
          $data['to_account_id'] = $to_account->id;
        }

        $data['status'] = 'Pending';
        $data['metastatus'] = isset($data['transfer']) ? json_encode(['Transfer ' => $data['transfer']]) : '{ "Transfer": "Offer" }' ;
        
        $queue = TransactionQueues::create($data);



        $deposit = Category::where('name', 'Deposit')->where('company_id', $to_company['id'])->first();
        $expense = Category::where('name', 'Other')->where('company_id', $from_company['id'])->first();

        $today = date_create();
        $t = time();
        $amount = 0;
        if (count($data['items']) === 0) {
          $amount = $data['amount'];
        }
        $document = [
          'document_number' => $t,
          // 'invoice_number' => $t,
          'items' => [],
          'currency_rate' => 1,
          'status' => 'draft',
          'amount' => $amount,
          'issued_at' => $today->format('Y-m-d H:i:s'),
          'due_at' => $today->modify('+1 day')->format('Y-m-d H:i:s'),
          'recurring_frequency' => 'no',
          'recurring_interval' => 1,
          'recurring_custom_frequency' => 'monthly',
          'recurring_count' => 0,
          'items' => $data['items'],
          'notes' => $data['notes'],
        ];
        // NOTE: for Deposite From Account and To Account both will generate Invoice
        // AND From Account will make To Account customer and To Account itself
        // for Transfer From Account Bill and To Account generate Invoice

        // NOTE: invoice -> customer
        // bill -> vendor

        if ($from_company)
        {
          for ($i=0; $i < count($data['items']); $i++) { 
            $from_amount = $this->dispatch(new ConvertCurrency($data['currency'], $data['from_currency'], $data['items'][$i]['price']));
            $document['items'][$i]['price'] = $from_amount;          
          }
          $document['currency_code'] = $data['from_currency'];
          $document['category_id'] = $expense['id'];
          if ($transactionType === 'Deposit')
          {
            // for deposite from company is earning too, so it is invoice
            $this->createInvoice($from_company['id'], $document, $data['to_attribution_id'], $data['to'], $queue['id']);
          }
          else
          {
            $this->createBill($from_company['id'], $document, $data['to_attribution_id'], $data['to'], $queue['id']);
          }
          
        }
        
        // INVOICE FOR PROVIDER
        if ($to_company)
        {
          for ($i=0; $i < count($data['items']); $i++) { 
            $to_amount = $this->dispatch(new ConvertCurrency($data['currency'], $data['to_currency'], $data['items'][$i]['price']));
            $document['items'][$i]['price'] = $to_amount;          
          }
          $document['currency_code'] = $data['to_currency'];
          $document['category_id'] = $deposit['id'];
          if ($transactionType === 'Deposit')
          {
            // for deposite self is the customer
            $this->createInvoice($to_company['id'], $document, $data['to_attribution_id'], $data['to'], $queue['id']);
          }
          else
          {
            $this->createInvoice($to_company['id'], $document, $data['from_attribution_id'], $data['from'], $queue['id']);

          }
        }

        DB::commit();
        return [ 'oatid' => $queue['id'] ];
      } catch (\Throwable $th) {
        DB::rollback();
        throw $th;
      }
        // create documents: one invoice and one bill
      return $this->transaction;
    }

    private function createBill($companyId, $document, $attr_id, $contact_data, $queue_id)
    {
      session(['company_id' => $companyId]);
      $contact = $this->dispatch(new CreateWalletContact('vendor', $companyId, $attr_id, $contact_data));
      $billDoc = $document;
      $billDoc['document_number'] = 'BILL-' . $billDoc['document_number'];
      $billDoc['type'] = 'bill';
      $billDoc['company_id'] = $companyId;
      $billDoc['contact_id'] = $contact->id;
      $billDoc['contact_name'] = $contact->name;
      $billDoc['contact_email'] = $contact->email;
      $billDoc['status'] = 'received';
      
      $bill = $this->dispatch(new CreateDocument($billDoc));
      $billH = $this->dispatch(new CreateDocumentHistory($bill, 0, trans('bills.mark_received')));
      TQRel::create(['oadid' => $bill['id'], 'waqid' => $queue_id ]);
    }

    private function createInvoice($companyId, $document, $attr_id, $contact_data, $queue_id)
    {
      session(['company_id' => $companyId]);
      $contact = $this->dispatch(new CreateWalletContact('customer', $companyId, $attr_id, $contact_data));        
      $invoiceDoc = $document;
      $invoiceDoc['document_number'] = 'INV-' . $invoiceDoc['document_number'];
      $invoiceDoc['type'] = 'invoice';
      $invoiceDoc['company_id'] = $companyId;
      $invoiceDoc['contact_id'] = $contact->id;
      $invoiceDoc['contact_name'] = $contact->name;
      $invoiceDoc['contact_email'] = $contact->email;
      $invoiceDoc['status'] = 'sent';
      
      $invoice = $this->dispatch(new CreateDocument($invoiceDoc));
      $invoiceH = $this->dispatch(new CreateDocumentHistory($invoice, 0, trans('invoices.mark_sent')));
      TQRel::create(['oadid' => $invoice['id'], 'waqid' => $queue_id ]);
    }

}
