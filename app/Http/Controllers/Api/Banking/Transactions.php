<?php

namespace App\Http\Controllers\Api\Banking;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Banking\Transaction as Request;
use App\Http\Resources\Banking\Transaction as Resource;
use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Banking\DeleteTransaction;
use App\Jobs\Banking\UpdateTransaction;
use App\Models\Banking\Transaction;
use Illuminate\Http\Request as ApiRequest;

class Transactions extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $transactions = Transaction::with('account', 'category', 'contact')->collect(['paid_at'=> 'desc']);

        return Resource::collection($transactions);
    }

    /**
     * Display the specified resource.
     *
     * @param  Transaction  $transaction
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Transaction $transaction)
    {
        return new Resource($transaction);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $transaction = $this->dispatch(new CreateTransaction($request));

        return $this->created(route('api.transactions.show', $transaction->id), new Resource($transaction));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $transaction
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Transaction $transaction, Request $request)
    {
        $transaction = $this->dispatch(new UpdateTransaction($transaction, $request));

        return new Resource($transaction->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        try {
            $this->dispatch(new DeleteTransaction($transaction));

            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Create Transaction
     * 
     * @param
     * @return \Illuminate\Http\Response
     */
    public function create(ApiRequest $request){
        function mapTransactionData($data){
            return "Data Received";
        }
        $data = mapDataForTransaction($request->all());
        return response()->json(['message' => 'Data stored successfully', 'data'=>$data]);
    }
}
