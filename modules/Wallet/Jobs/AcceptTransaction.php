<?php

namespace Modules\Wallet\Jobs;

use Illuminate\Support\Facades\DB;
use App\Abstracts\Job;
use App\Models\Common\Company;
use Modules\Wallet\Models\TransactionQueues;
use Modules\Wallet\Models\AtomicTransactions;
use Modules\Wallet\Models\TransactionQueueRelation as TQRel;
use Modules\Wallet\Models\TransactionRelation as TRel;
use Modules\Wallet\Models\Document;
use Modules\Wallet\Jobs\CreateBankingDocumentTransaction;
use Exception;
use Illuminate\Support\Facades\Log;

class AcceptTransaction extends Job
{

  protected $tq_id;
  protected $transaction;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tq_id)
    {
      $this->tq_id = $tq_id;
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
          $queue = TransactionQueues::find($this->tq_id);
          $transactionType = $queue['type'];
          if($queue['status'] === 'Accepted')
          {
            throw new Exception('Already Accepted', 1);
          }
          $request = [];
          $from_company = Company::where('attribute_id', $queue['from_attribution_id'])->first();
          $to_company = Company::where('attribute_id', $queue['to_attribution_id'])->first();
          $docIds = TQRel::where('waqid', $this->tq_id)->get();
          
          $queue['status'] = 'Accepted';
          $queue->save();
          $tranx = $queue->toArray();
          $tranx['id'] = null;
          $tranx['transaction_queue_id'] = $queue['id'];
          $tranx['status'] = 'Credited';
          $transaction = AtomicTransactions::create($tranx);
          $request['number'] = 'TRA-' . time();
          
          if ($from_company)
          {
            $docId = $docIds[0]['oadid'];
            // session(['company_id' => $from_company['id']]);
            $doc = Document::find($docId);
            $request['account_id'] = $queue['from_account_id'];
            $request['type'] = 'expense';
            $docTrnx = $this->dispatch(new CreateBankingDocumentTransaction($doc, $request));
            TRel::create(['oatid' => $docTrnx['id'], 'watid' => $transaction['id'] ]);
          }
          
          if ($to_company)
          {
            $invId = $docIds[1]['oadid']; 
            session(['company_id' => $to_company['id']]);
            $invoice = Document::where('id', '=', $invId)->first();
            $request['account_id'] = $queue['to_account_id'];
            $request['type'] = 'income';
            $invTrnx = $this->dispatch(new CreateBankingDocumentTransaction($invoice, $request));
            TRel::create(['oatid' => $invTrnx['id'], 'watid' => $transaction['id'] ]);
          }

          DB::commit();
          return [ 'oatid' => $queue['id'] ];
        } catch (\Throwable $th) {
          DB::rollback();
          throw $th;
        }
    }
}
