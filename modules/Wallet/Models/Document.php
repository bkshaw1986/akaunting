<?php

namespace Modules\Wallet\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';
    protected $fillable = [
        'company_id',
        'type',
        'document_number',
        'order_number',
        'status',
        'issued_at',
        'due_at',
        'amount',
        'currency_code',
        'currency_rate',
        'category_id',
        'contact_id',
        'contact_name',
        'contact_email',
        'contact_tax_number',
        'contact_phone',
        'contact_address',
        'contact_country',
        'contact_state',
        'contact_zip_code',
        'contact_city',
        'notes',
        'footer',
        'parent_id',
        'created_from',
        'created_by',
    ];

}
