<?php

namespace Modules\Wallet\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionRelation extends Model
{
    protected $table = 'transaction_relations';
    protected $fillable = ['watid', 'oatid'];

}
