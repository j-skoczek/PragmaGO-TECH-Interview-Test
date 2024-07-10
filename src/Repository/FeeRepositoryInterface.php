<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Repository;

use PragmaGoTech\Interview\Model\FeeRule;

interface FeeRepositoryInterface
{
    /**
     * Read fee rules and return them in a formatted way
     *
     * @param int $loanTerm
     *
     * @return FeeRule[] array of fee rules with loan amounts as keys
     */
    public function getFeeRules(int $loanTerm): array;
}
