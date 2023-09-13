<?php

namespace Modules\Wallet\Models;

use Illuminate\Database\Eloquent\Model;

class ConversionRate extends Model
{
    protected $table = 'conversion_rates';
    protected $fillable = ['from_currency', 'to_currency', 'rate'];
}
