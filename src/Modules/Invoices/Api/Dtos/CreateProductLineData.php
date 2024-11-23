<?php

declare(strict_types=1);

namespace Modules\Invoices\Api\Dtos;

final readonly class CreateProductLineData
{
    public function __construct(
        private string $name,
        private int $price,
        private int $quantity,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
