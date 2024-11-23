<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\Services;

use Modules\Invoices\Api\Dtos\InvoiceData;
use Modules\Invoices\Api\Dtos\CreateInvoiceData;
use Modules\Invoices\Domain\Entities\Invoice;
use Modules\Invoices\Domain\Entities\ProductLine;
use Illuminate\Support\Str;

class CreateInvoiceService extends AbstractInvoiceService
{
    public function execute(CreateInvoiceData $createInvoiceData): InvoiceData
    {
        $productLines = [];
        $invoiceId = (string) Str::uuid();

        foreach ($createInvoiceData->getProductLines() as $productLineDto) {
            $productLine = new ProductLine(
                (string) Str::uuid(),
                $invoiceId,
                $productLineDto->getName(),
                $productLineDto->getPrice(),
                $productLineDto->getQuantity(),
            );
            $productLines[] = $productLine;
        }

        $newInvoice = new Invoice(
            id: $invoiceId,
            customerName: $createInvoiceData->getCustomerName(),
            customerEmail: $createInvoiceData->getCustomerEmail(),
            productLines: $productLines
        );

        $invoice = $this->invoiceRepository->save($newInvoice);

        return $this->convertToDto($invoice);
    }
}
