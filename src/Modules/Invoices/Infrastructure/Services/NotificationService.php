<?php

declare(strict_types=1);

namespace Modules\Invoices\Infrastructure\Services;

use Ramsey\Uuid\Uuid;
use Modules\Invoices\Api\Dtos\SendInvoiceData;
use Modules\Invoices\Domain\Entities\Invoice;
use Modules\Invoices\Domain\Services\NotificationServiceInterface;
use Modules\Notifications\Api\Dtos\NotifyData;
use Modules\Notifications\Api\NotificationFacadeInterface;

class NotificationService implements NotificationServiceInterface
{
    private const DEFAULT_SUBJECT = 'Invoice notification';
    private const DEFAULT_MESSAGE = 'Invoice message';

    public function __construct(
        private readonly NotificationFacadeInterface $notificationFacade
    ) {
    }

    public function sendInvoiceNotification(Invoice $invoice, SendInvoiceData $sendInvoiceData): void
    {
        $this->notificationFacade->notify(
            new NotifyData(
                Uuid::fromString($invoice->getId()),
                $invoice->getCustomerEmail(),
                $sendInvoiceData->getSubject() ?? self::DEFAULT_SUBJECT,
                $sendInvoiceData->getMessage() ?? self::DEFAULT_MESSAGE
            )
        );
    }
}
