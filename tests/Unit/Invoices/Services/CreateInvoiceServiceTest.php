<?php

declare(strict_types=1);

namespace Tests\Unit\Invoices\Services;

use Modules\Invoices\Api\Dtos\InvoiceData;
use Modules\Invoices\Api\Dtos\ProductLineData;
use Modules\Invoices\Application\Services\CreateInvoiceService;
use Modules\Invoices\Application\Services\TotalsCalculationService;
use Modules\Invoices\Domain\Entities\Invoice;
use Modules\Invoices\Domain\Entities\ProductLine;
use Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use Modules\Invoices\Api\Dtos\CreateInvoiceData;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\TestCase;

final class CreateInvoiceServiceTest extends TestCase
{
    use WithFaker;

    private InvoiceRepositoryInterface $invoiceRepository;

    private CreateInvoiceService $createInvoiceService;

    protected function setUp(): void
    {
        $this->setUpFaker();

        $this->invoiceRepository = $this->createMock(InvoiceRepositoryInterface::class);
        $totalsCalculationService = new TotalsCalculationService();
        $this->createInvoiceService = new CreateInvoiceService($this->invoiceRepository, $totalsCalculationService);
    }

    public function testInvoiceCreatedWithoutProductLines(): void
    {
        $id = $this->faker->uuid;
        $customerName = $this->faker->name;
        $customerEmail = $this->faker->email;
        $status = 'draft';
        $productLines = [];
        $expected = new InvoiceData($id, $customerName, $customerEmail, $status, [], 0);

        $invoiceData = new CreateInvoiceData(
            $customerName,
            $customerEmail,
        );

        $this->invoiceRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Invoice::class))
            ->willReturn(new Invoice(
                $id,
                $customerName,
                $customerEmail,
                $status,
                $productLines,
            ));

        $result = $this->createInvoiceService->execute($invoiceData);

        $this->assertEquals($expected, $result);
    }

    public function testInvoiceCreatedWithProductLines(): void
    {
        $id = $this->faker->uuid;
        $customerName = $this->faker->name;
        $customerEmail = $this->faker->email;
        $status = 'draft';

        $productId1 = $this->faker->uuid;
        $productName1 = $this->faker->word;
        $productPrice1 = $this->faker->randomNumber();
        $productQty1 = $this->faker->randomNumber();

        $productId2 = $this->faker->uuid;
        $productName2 = $this->faker->word;
        $productPrice2 = $this->faker->randomNumber();
        $productQty2 = $this->faker->randomNumber();

        $productLines = [
            new ProductLine($productId1, $id, $productName1, $productPrice1, $productQty1),
            new ProductLine($productId2, $id, $productName2, $productPrice2, $productQty2),
        ];
        $expected = new InvoiceData(
            $id,
            $customerName,
            $customerEmail,
            $status,
            [
                new ProductLineData($productId1, $id, $productName1, $productPrice1, $productQty1, $productPrice1*$productQty1),
                new ProductLineData($productId2, $id, $productName2, $productPrice2, $productQty2, $productPrice2*$productQty2),
            ],
            $productPrice1*$productQty1 + $productPrice2*$productQty2
        );

        $invoiceData = new CreateInvoiceData(
            $customerName,
            $customerEmail,
        );

        $this->invoiceRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Invoice::class))
            ->willReturn(new Invoice(
                $id,
                $customerName,
                $customerEmail,
                $status,
                $productLines,
            ));

        $result = $this->createInvoiceService->execute($invoiceData);

        $this->assertEquals($expected, $result);
    }
}
