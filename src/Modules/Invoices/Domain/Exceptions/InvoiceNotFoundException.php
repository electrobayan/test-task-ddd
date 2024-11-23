<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InvoiceNotFoundException extends NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct('Invoice requested is not found.');
    }
}
