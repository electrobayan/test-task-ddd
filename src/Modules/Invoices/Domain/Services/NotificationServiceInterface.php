<?php

declare(strict_types=1);

namespace Modules\Invoices\Domain\Services;

use Modules\Invoices\Api\Dtos\SendInvoiceData;
use Modules\Invoices\Domain\Entities\Invoice;

interface NotificationServiceInterface
{
    public function sendInvoiceNotification(Invoice $invoice, SendInvoiceData $sendInvoiceData): void;
}
