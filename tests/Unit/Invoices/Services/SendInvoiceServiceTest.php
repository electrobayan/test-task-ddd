<?php

declare(strict_types=1);

namespace Tests\Unit\Invoices\Services;

use Modules\Invoices\Api\Dtos\InvoiceData;
use Modules\Invoices\Api\Dtos\ProductLineData;
use Modules\Invoices\Api\Dtos\SendInvoiceData;
use Modules\Invoices\Domain\Entities\Invoice;
use Modules\Invoices\Domain\Entities\ProductLine;
use Modules\Invoices\Domain\Services\NotificationServiceInterface;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Invoices\Application\Services\SendInvoiceService;
use Modules\Invoices\Application\Services\TotalsCalculationService;
use Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use PHPUnit\Framework\TestCase;

class SendInvoiceServiceTest extends TestCase
{
    use WithFaker;

    private InvoiceRepositoryInterface $invoiceRepository;

    private SendInvoiceService $sendInvoiceService;

    protected function setUp(): void
    {
        $this->setUpFaker();

        $this->invoiceRepository = $this->createMock(InvoiceRepositoryInterface::class);
        $notificationService = $this->createMock(NotificationServiceInterface::class);
        $totalsCalculationService = new TotalsCalculationService();
        $this->sendInvoiceService = new SendInvoiceService(
            $this->invoiceRepository,
            $totalsCalculationService,
            $notificationService
        );
    }

    public function testSendInvoice(): void
    {
        $id = $this->faker->uuid;
        $customerName = $this->faker->name;
        $customerEmail = $this->faker->email;
        $status = 'draft';

        $productId1 = $this->faker->uuid;
        $productName1 = $this->faker->word;
        $productPrice1 = $this->faker->randomNumber();
        $productQty1 = $this->faker->randomNumber();

        $productLines = [
            new ProductLine($productId1, $id, $productName1, $productPrice1, $productQty1),
        ];

        $expected = new InvoiceData(
            $id,
            $customerName,
            $customerEmail,
            'sending',
            [
                new ProductLineData($productId1, $id, $productName1, $productPrice1, $productQty1, $productPrice1 * $productQty1),
            ],
            $productPrice1 * $productQty1
        );

        $this->invoiceRepository->expects($this->once())
            ->method('findById')
            ->willReturn(new Invoice(
                $id,
                $customerName,
                $customerEmail,
                $status,
                $productLines,
            ));
        $this->invoiceRepository->expects($this->once())
            ->method('save');

        $result = $this->sendInvoiceService->execute(
            $id,
            new SendInvoiceData('Test subject', 'Test message')
        );

        $this->assertEquals($expected, $result);
    }
}
