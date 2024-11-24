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
use Modules\Invoices\Application\Services\SendInvoiceService;

final class InvoiceController extends Controller
{
    public function __construct(
        private readonly ViewInvoiceService $viewInvoiceService,
        private readonly CreateInvoiceService $createInvoiceService,
        private readonly SendInvoiceService $sendInvoiceService
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

    public function send(string $id): InvoiceResource
    {
        $invoice = $this->sendInvoiceService->execute($id);

        return new InvoiceResource($invoice);
    }
}
