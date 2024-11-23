<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\Services;

use Modules\Invoices\Api\Dtos\InvoiceData;

final class ViewInvoiceService extends AbstractInvoiceService
{
    public function execute(string $id): InvoiceData
    {
        $invoice = $this->invoiceRepository->findById($id);

        return $this->convertToDto($invoice);
    }
}
