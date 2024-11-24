<?php

declare(strict_types=1);

namespace Tests\Unit\Invoices\Services;

use Illuminate\Foundation\Testing\WithFaker;
use Modules\Invoices\Api\Dtos\InvoiceData;
use Modules\Invoices\Api\Dtos\ProductLineData;
use Modules\Invoices\Application\Services\ViewInvoiceService;
use Modules\Invoices\Application\Services\TotalsCalculationService;
use Modules\Invoices\Domain\Entities\Invoice;
use Modules\Invoices\Domain\Entities\ProductLine;
use Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ViewInvoiceServiceTest extends TestCase
{
    use WithFaker;

    private InvoiceRepositoryInterface $invoiceRepository;

    private ViewInvoiceService $viewInvoiceService;

    protected function setUp(): void
    {
        $this->setUpFaker();

        $this->invoiceRepository = $this->createMock(InvoiceRepositoryInterface::class);
        $totalsCalculationService = new TotalsCalculationService();

        $this->viewInvoiceService = new ViewInvoiceService($this->invoiceRepository, $totalsCalculationService);
    }

    public function testInvoiceReturned(): void
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
            $status,
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

        $result = $this->viewInvoiceService->execute($id);

        $this->assertEquals($expected, $result);
    }
}
