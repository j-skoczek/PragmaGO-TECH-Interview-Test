<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Service;

use PragmaGoTech\Interview\Model\FeeRule;
use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\Service\FeeCalculatorInterface as ServiceFeeCalculatorInterface;

class FeeCalculator implements ServiceFeeCalculatorInterface
{
    private Math $mathService;

    /**
     * Loan + fee must be divisible by given nominative, value below is in centesimal
     */
    const TOTAL_LOAN_NOMINATIVE = 500;

    public function __construct(Math $mathService)
    {
        $this->mathService = $mathService;
    }

    /**
     * Calculate a fee for given LoanProposal based on given FeeRules
     *
     * @param LoanProposal $loanProposal
     * @param FeeRule[] $feeRules an array of defined fee rules with keys as loan amounts
     *
     * @return float The calculated total fee.
     */
    public function calculateFee(LoanProposal $loanProposal, array $feeRules): float
    {
        $closestDefinedAmounts = $this->mathService->findClosestValues(
            $loanProposal->centesimalAmount(),
            array_keys($feeRules)
        );

        $lowerAmount = min($closestDefinedAmounts);
        $higherAmount = max($closestDefinedAmounts);
        $lowerFee = ($feeRules[$lowerAmount])->centesimalFee();
        $higherFee = ($feeRules[$higherAmount])->centesimalFee();

        $interpolatedCentesimalFee = $this->mathService->interpolate(
            $loanProposal->centesimalAmount(),
            $lowerAmount,
            $lowerFee,
            $higherAmount,
            $higherFee
        );

        $centesimalFee = $this->roundFee($interpolatedCentesimalFee, $loanProposal->centesimalAmount());

        return $this->castFeeToCurrencyFormat($centesimalFee);
    }

    /**
     * Round the fee so that the sum of the fee and the loan amount is an exact multiple of TOTAL_LOAN_NOMINATIVE.
     *
     * @param int $rawFee
     * @param int $amount
     *
     * @return int
     */
    protected function roundFee(int $rawFee, int $amount): int
    {
        $rawTotalCost = $rawFee + $amount;

        if ($rawTotalCost % self::TOTAL_LOAN_NOMINATIVE === 0) {
            return $rawFee;
        } else {
            $expectedTotal = ((int)ceil($rawTotalCost / self::TOTAL_LOAN_NOMINATIVE)) * self::TOTAL_LOAN_NOMINATIVE;
            return $expectedTotal - $amount;
        }
    }

    /**
     * Cast fee to a format XXXX.XX
     * @param int $centesimalFee
     *
     * @return float
     */
    protected function castFeeToCurrencyFormat(int $centesimalFee): float
    {
        return (int)($centesimalFee / 100);
    }
}
