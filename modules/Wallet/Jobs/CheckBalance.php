<?php

namespace Modules\Wallet\Jobs;

use App\Abstracts\Job;
use App\Models\Banking\Account;
use App\Models\Common\Company;
use Modules\Wallet\Jobs\ConvertCurrency;


class CheckBalance extends Job
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
    session([ 'company_id' => $company->id, 'show_accounts' => true ]);
    $account = Account::where('company_id', $company->id)->where('currency_code', $this->request['currency'])->first();
    $balance = $account->balance;
    $converted_balance = $this->dispatch(new ConvertCurrency($this->request['currency'], 'USD', $balance));
    return $converted_balance;
  }
}