<?php

namespace Modules\Wallet\Jobs;

use App\Abstracts\Job;
use Illuminate\Support\Facades\DB;
use Modules\Wallet\Jobs\CreateTransaction;
use Modules\Wallet\Jobs\AcceptTransaction;

class GrantTransaction extends Job
{

    protected $request;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
      $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      try {
        //code...
        $transaction = $this->dispatch(new CreateTransaction($this->request));
        $accept = $this->dispatch(new AcceptTransaction($transaction['oatid']));
        return true;
      } catch (\Throwable $th) {
        return false;
      }
    }

}
