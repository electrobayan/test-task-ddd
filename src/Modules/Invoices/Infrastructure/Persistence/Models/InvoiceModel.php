<?php

declare(strict_types=1);

namespace Modules\Invoices\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvoiceModel extends Model
{
    protected $table = 'invoices';

    protected $fillable = [
        'id',
        'customer_name',
        'customer_email',
        'status',
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    public function productLines(): HasMany
    {
        return $this->hasMany(ProductLineModel::class, 'invoice_id');
    }
}
