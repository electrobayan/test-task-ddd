<?php

declare(strict_types=1);

namespace Modules\Invoices\Presentation\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Invoices\Api\Dtos\ProductLineData;

class ProductLineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var $this ProductLineData */
        return [
            'productName' => $this->getProductName(),
            'quantity' => $this->getQuantity(),
            'unitPrice' => $this->getPrice(),
            'totalUnitPrice' => $this->getTotalUnitPrice(),
        ];
    }
}
