<?php

declare(strict_types=1);

namespace Invoices\Listeners;

use Modules\Notifications\Api\Events\ResourceDeliveredEvent;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Invoices\Application\Listeners\ResourceDeliveredListener;
use Modules\Invoices\Domain\Entities\Invoice;
use Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ResourceDeliveredListenerTest extends TestCase
{
    use WithFaker;

    private InvoiceRepositoryInterface $invoiceRepository;

    private ResourceDeliveredListener $deliveredListener;

    protected function setUp(): void
    {
        $this->setUpFaker();

        $this->invoiceRepository = $this->createMock(InvoiceRepositoryInterface::class);

        $this->deliveredListener = new ResourceDeliveredListener($this->invoiceRepository);
    }

    public function testHandle(): void
    {
        $id = $this->faker->uuid;
        $customerName = $this->faker->name;
        $customerEmail = $this->faker->email;
        $status = 'sending';
        $invoice = new Invoice(
            $id,
            $customerName,
            $customerEmail,
            $status,
            [],
        );

        $this->invoiceRepository->expects($this->once())
            ->method('findById')
            ->willReturn($invoice);

        $this->deliveredListener->handle(new ResourceDeliveredEvent(Uuid::fromString($id)));

        $this->assertEquals('sent-to-client', $invoice->getStatus());
    }
}
