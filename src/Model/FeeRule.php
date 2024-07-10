<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Model;

class FeeRule
{
    private int $fee;

    private float $amount;

    public function __construct(float $amount, int $fee)
    {
        $this->amount = $amount;
        $this->fee = $fee;
    }

    /**
     * A loan amount with given fee.
     *
     * @return float
     */
    public function amount(): float
    {
        return $this->amount;
    }

    /**
     * A loan amount with given fee in centesimal.
     *
     * @return int
     */
    public function centesimalAmount(): int
    {
        return (int)($this->amount * 100);
    }

    /**
     * Fee for given loan amount.
     *
     * @return float
     */
    public function fee(): int
    {
        return $this->fee;
    }

    /**
     * ee for given loan amount in centesimal.
     *
     * @return int
     */
    public function centesimalFee(): int
    {
        return (int)($this->fee * 100);
    }
}
