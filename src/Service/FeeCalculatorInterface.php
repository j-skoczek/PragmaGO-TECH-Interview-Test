<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Service;

use PragmaGoTech\Interview\Model\LoanProposal;

interface FeeCalculatorInterface
{
    /**
     * @return float The calculated total fee.
     */
    public function calculateFee(LoanProposal $loanProposal, array $feeRules): float;
}