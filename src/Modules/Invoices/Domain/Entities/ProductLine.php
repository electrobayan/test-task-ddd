<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Entities;

readonly class ProductLine
{
    public function __construct(
        private string $id,
        private string $invoiceId,
        private string $name,
        private int $price,
        private int $quantity
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getInvoiceId(): string
    {
        return $this->invoiceId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function isValid(): bool
    {
        return is_int($this->quantity) && $this->quantity > 0 && is_int($this->price) && $this->price > 0;
    }
}
