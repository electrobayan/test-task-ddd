<?php

declare(strict_types=1);

namespace Modules\Invoices\Api\Dtos;

final readonly class SendInvoiceData
{
    public function __construct(
        private string $subject,
        private string $message
    ) {}

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
