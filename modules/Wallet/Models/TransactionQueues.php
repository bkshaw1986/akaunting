<?php

namespace Modules\Wallet\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Akaunting\Sortable\Traits\Sortable;

class TransactionQueues extends Model
{
    use Sortable, SoftDeletes;

    protected $table = 'transaction_queues';
    protected $fillable = ['amount', 'type', 'metastatus', 'status', 'from_attribution_id', 'to_attribution_id', 'from_pe', 'by_pe', 'to_pe', 'from_account_id', 'to_account_id', 't1', 't2'];
    protected $dates = ['deleted_at'];
    
}
