<?php

namespace Modules\Wallet\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Akaunting\Sortable\Traits\Sortable;
class AtomicTransactions extends Model
{
  use Sortable, SoftDeletes;

  protected $table = 'atomic_transactions';
  protected $dates = ['deleted_at'];
  protected $fillable = ['amount', 'transaction_queue_id', 'type', 'metastatus', 'status', 'from_attribution_id', 'to_attribution_id', 'from_pe', 'by_pe', 'to_pe', 'from_account_id', 'to_account_id', 't1', 't2'];

}
