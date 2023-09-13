<?php

namespace Modules\Wallet\Jobs;

use App\Abstracts\Job;
use Modules\Wallet\Models\ConversionRate;


class ConvertCurrency extends Job
{
  protected $from_currency;
  protected $to_currency;
  protected $amount;

  public function __construct($from_currency, $to_currency, $amount)
    {
      $this->from_currency = $from_currency;
      $this->to_currency = $to_currency;
      $this->amount = $amount;
    }

    public function handle()
    {
      if ($this->from_currency === $this->to_currency)
      {
        return $this->amount;
      }
      else
      {
        $convertRate = ConversionRate::where('from_currency', $this->from_currency)->where('to_currency', $this->to_currency)->first();
        if ($convertRate)
        {
          $rate = $convertRate->rate;
          return (double) $this->amount * (double) $rate;
        }
        $convertRate = ConversionRate::where('from_currency', $this->to_currency)->where('to_currency', $this->from_currency)->first();
        if ($convertRate)
        {
          $rate = $convertRate->rate;
          return (double) $this->amount / (double) $rate;
        }
        else
        {
          return $this->amount;
        }
        
      }
    }
}