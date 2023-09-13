<?php

namespace Modules\Wallet\Jobs;

use App\Abstracts\Job;
use App\Jobs\Banking\CreateAccount;


class FindAccount extends Job
{
  protected $company;
  protected $accountName;

  public function __construct($company, $source = 'booking', $currency = 'USD')
    {
      $this->company = $company;
      if ($source === 'booking' && $currency == 'USD') {
        $this->accountName = 'Oxygen Booking';
      } elseif ($currency == 'CRD')
      {
        $this->accountName = 'Simplia Credit';
      }
      elseif ($currency == 'PNT')
      {
        $this->accountName = 'Simplia Points';
      } 
      else
      {
        $this->accountName = 'Cash';
      }
    }

    public function handle()
    {
      // TODO: needs to improve
      $accounts = $this->company->accounts()->get();
      $account = null;
      for ($i=0; $i < count($accounts); $i++) { 
        $acc = $accounts[$i];
        if ($acc->name == $this->accountName)
        {
          $account = $acc;
          break;
        }
      }
      if (!$account && in_array($this->accountName, ['Oxygen Booking']))
      {
        $acc = [
          'name' => 'Oxygen Booking',
          'company_id' => $this->company->id,
          'number' => 4,
          'default_account' => 0,
          'enabled' => 1,
          'currency_code' => 'USD',
          'opening_balance' => 0,
        ];
        $account = $this->dispatch(new CreateAccount($acc));
      }
      return $account;
    }
}