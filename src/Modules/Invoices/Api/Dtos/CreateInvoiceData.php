<?php

declare(strict_types=1);

namespace Modules\Invoices\Api\Dtos;

final readonly class CreateInvoiceData
{
    public function __construct(
        private string $customerName,
        private string $customerEmail,
        private array $productLines = [],
    ) {}

    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    /**
     * @return CreateProductLineData[]
     */
    public function getProductLines(): array
    {
        return $this->productLines;
    }
}
