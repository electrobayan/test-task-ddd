<?php

declare(strict_types=1);

namespace Modules\Invoices\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductLineModel extends Model
{
    protected $table = 'invoice_product_lines';

    protected $fillable = [
        'id',
        'invoice_id',
        'name',
        'price',
        'quantity'
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(InvoiceModel::class, 'invoice_id');
    }
}
