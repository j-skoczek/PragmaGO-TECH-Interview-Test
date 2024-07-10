<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Controller;

interface FeeCalculatorInterface
{
    /**
     * Calculate fee for given loan proposal
     *
     * @return float
     */
    public function calculateFee(): float;
}
