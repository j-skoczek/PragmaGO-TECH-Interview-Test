<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Service;

use PragmaGoTech\Interview\Model\FeeRule;
use PragmaGoTech\Interview\Model\LoanProposal;

interface FeeCalculatorInterface
{
    /**
     * @param LoanProposal $loanProposal
     * @param FeeRule[] $feeRules an array of defined fee rules with keys as loan amounts
     *
     * @return float The calculated total fee.
     */
    public function calculateFee(LoanProposal $loanProposal, array $feeRules): float;
}
