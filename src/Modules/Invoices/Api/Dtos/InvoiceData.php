<?php

declare(strict_types=1);

namespace Modules\Invoices\Api\Dtos;

final readonly class InvoiceData
{
    public function __construct(
        private string $id,
        private string $customerName,
        private string $customerEmail,
        private string $status,
        private array $productLines,
        private int $totalPrice
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getProductLines(): array
    {
        return $this->productLines;
    }

    public function getTotalPrice(): int
    {
        return $this->totalPrice;
    }
}
