<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Entities;

use Modules\Invoices\Domain\Enums\StatusEnum;
use Modules\Invoices\Domain\Exceptions\InvalidInvoiceOperationException;
use Modules\Invoices\Domain\Exceptions\InvoiceCannotBeSentException;

class Invoice
{
    public function __construct(
        private readonly string $id,
        private readonly string $customerName,
        private readonly string $customerEmail,
        private string $status = StatusEnum::Draft->value,
        private readonly array $productLines = [],
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

    public function markAsSending(): self
    {
        if (!$this->isDraft()) {
            throw new InvoiceCannotBeSentException('Invoice can be sent only from draft status.');
        }

        if (!$this->hasValidProductLines()) {
            throw new InvoiceCannotBeSentException('Invoice has incorrect product lines.');
        }

        $this->status = StatusEnum::Sending->value;

        return $this;
    }

    private function isDraft(): bool
    {
        return $this->status === StatusEnum::Draft->value;
    }

    private function hasValidProductLines(): bool
    {
        $result = false;

        if (!empty($this->productLines)) {
            /** @var $productLine ProductLine */
            foreach ($this->productLines as $productLine) {
                if ($productLine->isValid()) {
                    $result = true;

                    break;
                }
            }
        }

        return $result;
    }

    public function markAsSent(): self
    {
        if ($this->getStatus() !== StatusEnum::Sending->value) {
            throw new InvalidInvoiceOperationException();
        }

        $this->status = StatusEnum::SentToClient->value;

        return $this;
    }
}
