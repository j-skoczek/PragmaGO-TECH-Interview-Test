<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Model;

use Exception;

/**
 * A cut down version of a loan application containing
 * only the required properties for this test.
 */
class LoanProposal
{
    protected float $minLoanAmount = 1000.00;
    protected float $maxLoanAmount = 20000.00;
    protected int $minLoanTerm = 12;
    protected int $maxLoanTerm = 24;

    const INCORRECT_LOAN_AMOUNT = 'incorrect loan amount';
    const INCORRECT_LOAN_FORMAT = 'incorrect loan format';
    const INCORRECT_LOAN_TERM = 'incorrect loan term';

    private int $term;
    private float $amount;

    public function __construct(int $term, float $amount)
    {
        $this->validate($term, $amount);
        $this->term = $term;
        $this->amount = $amount;
    }

    /**
     * Validate data for value object
     *
     * @param int $term
     * @param float $amount
     */
    protected function validate(int $term, float $amount): void
    {
        $this->validateLoanAmount($amount);
        $this->validateNumberOfDecimalDigits($amount);
        $this->validateLoanTerm($term);
    }

    /**
     * Check if loan amount is withing given range
     *
     * @param float $amount
     */
    protected function validateLoanAmount(float $amount): void
    {
        if (
            $amount < $this->minLoanAmount ||
            $amount > $this->maxLoanAmount ||
            $amount <= 0
        ) {
            throw new Exception(self::INCORRECT_LOAN_AMOUNT);
        }
    }

    /**
     * Check if loan amount is in XXX.XX format
     *
     * @param float $amount
     */
    protected function validateNumberOfDecimalDigits(float $amount): void
    {
        if (strpos(strrev((string) $amount), ".") > 2) {
            throw new Exception(self::INCORRECT_LOAN_FORMAT);
        }
    }

    /**
     * Check if loan term is within given set
     *
     * @param int $term
     */
    protected function validateLoanTerm(int $term): void
    {
        if ($term !== $this->minLoanTerm && $term !== $this->maxLoanTerm) {
            throw new Exception(self::INCORRECT_LOAN_TERM);
        }
    }

    /**
     * Term (loan duration) for this loan application
     * in number of months.
     *
     * @return int
     */
    public function term(): int
    {
        return $this->term;
    }

    /**
     * Amount requested for this loan application.
     *
     * @return float
     */
    public function amount(): float
    {
        return $this->amount;
    }

    /**
     * Amount requested for this loan application in centesimal.
     *
     * @return int
     */
    public function centesimalAmount(): int
    {
        return (int)($this->amount * 100);
    }
}
