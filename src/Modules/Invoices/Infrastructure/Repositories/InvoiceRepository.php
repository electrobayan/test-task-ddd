<?php

declare(strict_types=1);

namespace Modules\Invoices\Infrastructure\Repositories;

use Modules\Invoices\Domain\Exceptions\InvoiceNotFoundException;
use Modules\Invoices\Domain\Entities\Invoice;
use Modules\Invoices\Domain\Entities\ProductLine;
use Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use Modules\Invoices\Infrastructure\Persistence\Models\InvoiceModel;
use Modules\Invoices\Infrastructure\Persistence\Models\ProductLineModel;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function save(Invoice $invoice): Invoice
    {
        $invoiceModel = InvoiceModel::find($invoice->getId()) ?? new InvoiceModel();

        $invoiceModel->id = $invoice->getId();
        $invoiceModel->status = $invoice->getStatus();
        $invoiceModel->customer_name = $invoice->getCustomerName();
        $invoiceModel->customer_email = $invoice->getCustomerEmail();
        $invoiceModel->save();

        foreach ($invoice->getProductLines() as $productLine) {
            $productLineModel = ProductLineModel::find($productLine->getId()) ?? new ProductLineModel();
            $productLineModel->id = $productLine->getId();
            $productLineModel->invoice_id = $invoiceModel->id;
            $productLineModel->name = $productLine->getName();
            $productLineModel->quantity = $productLine->getQuantity();
            $productLineModel->price = $productLine->getPrice();
            $productLineModel->save();
        }

        return $this->convertIntoEntity($invoiceModel);
    }

    public function findById(string $id): Invoice
    {
        $invoiceModel = InvoiceModel::with('productLines')->find($id);

        if (!$invoiceModel) {
            throw new InvoiceNotFoundException();
        }

        return $this->convertIntoEntity($invoiceModel);
    }

    private function convertIntoEntity(InvoiceModel $invoiceModel): Invoice
    {
        $productLines = [];

        foreach ($invoiceModel->productLines()->getModels() as $productLineModel) {

            $productLine = new ProductLine(
                $productLineModel->id,
                $productLineModel->invoice_id,
                $productLineModel->name,
                $productLineModel->price,
                $productLineModel->quantity,
            );
            $productLines[] = $productLine;
        }

        return new Invoice(
            $invoiceModel->id,
            $invoiceModel->customer_name,
            $invoiceModel->customer_email,
            $invoiceModel->status,
            $productLines,
        );
    }
}
