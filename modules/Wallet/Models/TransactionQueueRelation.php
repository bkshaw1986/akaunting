<?php

namespace Modules\Wallet\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionQueueRelation extends Model
{
  protected $table = 'transaction_queue_relations';
  protected $fillable = ['waqid', 'oadid'];
}
