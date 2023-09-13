<?php

namespace Modules\Wallet\Http\Controllers;

use App\Abstracts\Http\ApiController;
use Modules\Wallet\Jobs\CreateTransaction;
use Modules\Wallet\Jobs\AcceptTransaction;
use Modules\Wallet\Jobs\GrantTransaction;
use Modules\Wallet\Jobs\CheckBalance;
use Modules\Wallet\Jobs\AddPoints;
use Modules\Wallet\Http\Requests\Transaction as Request;

class Transaction extends ApiController
{
    public function createTransaction(Request $request)
    {
      $transaction = $this->dispatch(new CreateTransaction($request));
      return response()->json($transaction);
    }
    public function acceptTransaction(Request $request)
    {
      $transaction = $this->dispatch(new AcceptTransaction($request['id']));
      return response()->json($transaction);
      // return Document::find(1);
    }
    public function grantTransaction(Request $request)
    {
      $transaction = $this->dispatch(new GrantTransaction($request));
      return response()->json($transaction);
    }

    public function checkBalance(Request $request)
    {
      $balance = $this->dispatch(new CheckBalance($request));
      return response()->json($balance);
    }

    public function addPoints(Request $request)
    {
      return $this->dispatch(new AddPoints($request));
    }
    
}
