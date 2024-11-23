<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\Services;

use Modules\Invoices\Api\Dtos\ProductLineData;

class TotalsCalculationService
{
    public function calculateLineItemTotalPrice(int $price, int $quantity): int
    {
        return $price * $quantity;
    }

    /**
     * @param ProductLineData[] $productLines
     * @return int
     */
    public function calculateInvoiceTotalPrice(array $productLines): int
    {
        $totals = 0;

        foreach ($productLines as $productLine) {
            $totals += (int) $productLine->getTotalUnitPrice();
        }

        return $totals;
    }
}
