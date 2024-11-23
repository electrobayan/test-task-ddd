<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\Services;

use Modules\Invoices\Api\Dtos\InvoiceData;
use Modules\Invoices\Api\Dtos\ProductLineData;
use Modules\Invoices\Domain\Entities\Invoice;
use Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;

abstract class AbstractInvoiceService
{
    public function __construct(
        protected InvoiceRepositoryInterface $invoiceRepository,
        protected TotalsCalculationService $totalsCalculationService
    ) {
    }

    protected function convertToDto(Invoice $invoice): InvoiceData
    {
        $productLines= $this->prepareProductLines($invoice);
        $invoiceTotalPrice = $this->totalsCalculationService->calculateInvoiceTotalPrice($productLines);

        return new InvoiceData(
            $invoice->getId(),
            $invoice->getCustomerName(),
            $invoice->getCustomerEmail(),
            $invoice->getStatus(),
            $productLines,
            $invoiceTotalPrice
        );
    }

    private function prepareProductLines(Invoice $invoice): array
    {
        return array_map(function ($productLine) {
            $totalUnitPrice = $this->totalsCalculationService->calculateLineItemTotalPrice(
                $productLine->getPrice(),
                $productLine->getQuantity()
            );

            return new ProductLineData(
                $productLine->getId(),
                $productLine->getInvoiceId(),
                $productLine->getName(),
                $productLine->getPrice(),
                $productLine->getQuantity(),
                $totalUnitPrice
            );
        }, $invoice->getProductLines());
    }
}
