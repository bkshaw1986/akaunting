<?php

namespace Modules\Wallet\Jobs;

use App\Abstracts\Job;
use App\Models\Common\Company;
use App\Models\Setting\Category;
use App\Jobs\Banking\CreateTransaction;
use Modules\Wallet\Jobs\FindAccount;

class AddPoints extends Job
{
  protected $request;
  public function __construct($request)
  {
    $this->request = $this->getRequestInstance($request);
  }

  public function handle()
  {
    $data = $this->request->all();
    $company = Company::where('attribute_id', $data['attribute_id'])->first();
    $deposit = Category::where('name', 'Deposit')->where('company_id', $company['id'])->first();
    $acc = $this->dispatch(new FindAccount($company, 'simplia', 'PNT'));
    $data['company_id'] = $company->id;
    $data['currency_code'] = 'PNT';
    $data['currency_rate'] = 0;
    $data['type'] = 'income';
    $data['description'] = 'Ponints from Simplia';
    $data['paid_at'] = date('Y-m-d');
    $data['account_id'] = $acc->id;
    $data['category_id'] = $deposit->id;
    $data['recurring_frequency'] = 'no';
    $data['payment_method'] = 'offline-payments.cash.1';
    $points = $this->dispatch(new CreateTransaction($data));
    return $points;
    
  }
}