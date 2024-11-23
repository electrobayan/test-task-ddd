<?php

declare(strict_types=1);

namespace Modules\Invoices\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Modules\Invoices\Api\Dtos\InvoiceData;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var $this InvoiceData */
        return [
            'id' => $this->getId(),
            'status' => $this->getStatus(),
            'customerName' => $this->getCustomerName(),
            'customerEmail' => $this->getCustomerEmail(),
            'productLines' => ProductLineResource::collection($this->getProductLines()),
            'totalPrice' => $this->getTotalPrice(),
        ];
    }
}
