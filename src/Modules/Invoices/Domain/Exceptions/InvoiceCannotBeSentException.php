<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Exceptions;

use DomainException;

class InvoiceCannotBeSentException extends DomainException
{
    public function __construct(
        string $message = 'Cannot send invoice.',
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
