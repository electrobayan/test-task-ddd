<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\Listeners;

use Modules\Invoices\Domain\Exceptions\InvalidInvoiceOperationException;
use Modules\Notifications\Api\Events\ResourceDeliveredEvent;
use Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;

final readonly class ResourceDeliveredListener
{
    public function __construct(
        private InvoiceRepositoryInterface $invoiceRepository
    ) {
    }

    /**
     * @param ResourceDeliveredEvent $event
     * @return void
     * @throws InvalidInvoiceOperationException
     */
    public function handle(ResourceDeliveredEvent $event): void
    {
        $invoiceId = $event->resourceId->toString();

        $invoice = $this->invoiceRepository->findById($invoiceId);

        $this->invoiceRepository->save($invoice->markAsSent());
    }
}
