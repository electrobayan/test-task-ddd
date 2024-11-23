<?php

declare(strict_types=1);

namespace Modules\Invoices\Presentation\Http;

use Illuminate\Routing\Controller;
use Modules\Invoices\Api\Dtos\CreateInvoiceData;
use Modules\Invoices\Api\Dtos\CreateProductLineData;
use Modules\Invoices\Presentation\Requests\CreateInvoiceRequest;
use Modules\Invoices\Presentation\Resources\InvoiceResource;
use Modules\Invoices\Application\Services\ViewInvoiceService;
use Modules\Invoices\Application\Services\CreateInvoiceService;

final class InvoiceController extends Controller
{
    public function __construct(
        private readonly ViewInvoiceService $viewInvoiceService,
        private readonly CreateInvoiceService $createInvoiceService,
    ) {
    }

    public function view(string $id): InvoiceResource
    {
        $invoiceData = $this->viewInvoiceService->execute($id);

        return new InvoiceResource($invoiceData);
    }

    public function create(CreateInvoiceRequest $request): InvoiceResource
    {
        $validatedData = $request->validated();

        $productLineDtos = [];
        if (!empty($validatedData['productLines'])) {
            foreach ($validatedData['productLines'] as $productLineData) {
                $productLineDto = new CreateProductLineData(
                    $productLineData['productName'],
                    $productLineData['price'],
                    $productLineData['quantity'],
                );
                $productLineDtos[] = $productLineDto;
            }
        }

        $createInvoiceDto = new CreateInvoiceData(
            $validatedData['customerName'],
            $validatedData['customerEmail'],
            $productLineDtos
        );

        $invoice = $this->createInvoiceService->execute($createInvoiceDto);

        return new InvoiceResource($invoice);
    }

    /**
     * An invoice can only be sent if it is in draft status.
     * An invoice can only be marked as sent-to-client if its current status is sending.
     * To be sent, an invoice must contain product lines with both quantity and unit price as positive integers greater than zero.
     *
     * Send an email notification to the customer using the NotificationFacade.
     * The email's subject and message may be hardcoded or customized as needed.
     * Change the Invoice Status to sending after sending the notification.
     *
     * Upon successful delivery by the Dummy notification provider:
     * The Notification Module triggers a ResourceDeliveredEvent via webhook.
     * The Invoice Module listens for and captures this event.
     * The Invoice Status is updated from sending to sent-to-client.
     * Note: This transition requires that the invoice is currently in the sending status.
     */
    public function send(int $id)
    {

    }
}
