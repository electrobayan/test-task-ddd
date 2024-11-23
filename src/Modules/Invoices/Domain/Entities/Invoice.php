<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Entities;

use Modules\Invoices\Domain\Enums\StatusEnum;

readonly class Invoice
{
    public function __construct(
        private string $id,
        private string $customerName,
        private string $customerEmail,
        private string $status = StatusEnum::Draft->value,
        private array $productLines = [],
    ) {
    }

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

    /**
     * @return ProductLine[]
     */
    public function getProductLines(): array
    {
        return $this->productLines;
    }
}
