<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\Services;

use Modules\Invoices\Api\Dtos\InvoiceData;
use Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use Modules\Invoices\Domain\Services\NotificationServiceInterface;

class SendInvoiceService extends AbstractInvoiceService
{
    public function __construct(
        InvoiceRepositoryInterface $invoiceRepository,
        TotalsCalculationService $totalsCalculationService,
        private readonly NotificationServiceInterface $notificationService
    ) {
        parent::__construct($invoiceRepository, $totalsCalculationService);
    }

    public function execute(string $id): InvoiceData
    {
        $invoice = $this->invoiceRepository->findById($id);

        $this->invoiceRepository->save($invoice->markAsSending());
        $this->notificationService->sendInvoiceNotification($invoice);

        return $this->convertToDto($invoice);
    }
}
