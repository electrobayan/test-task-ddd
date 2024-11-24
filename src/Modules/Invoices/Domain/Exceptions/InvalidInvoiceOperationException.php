<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Exceptions;

use Throwable;
use Exception;

class InvalidInvoiceOperationException extends Exception
{
    public function __construct(
        string $message = "Cannot change invoice status as it is not valid. It must be in the sending status.",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
