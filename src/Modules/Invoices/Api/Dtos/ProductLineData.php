<?php

declare(strict_types=1);

namespace Modules\Invoices\Api\Dtos;

final readonly class ProductLineData
{
    public function __construct(
        private string $id,
        private string $invoiceId,
        private string $productName,
        private int $price,
        private int $quantity,
        private int $totalUnitPrice = 0
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getInvoiceId(): string
    {
        return $this->invoiceId;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getTotalUnitPrice(): ?int
    {
        return $this->totalUnitPrice;
    }
}
