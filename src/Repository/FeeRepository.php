<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Repository;

use Exception;
use PragmaGoTech\Interview\Model\FeeRule;

class FeeRepository implements FeeRepositoryInterface
{
    const FEE_SEPARATOR = ':';
    const TWELVE_MONTH_FEES_PATH = '/src/Resources/12-month-fees.txt';
    const TWENTY_FOUR_MONTH_FEES_PATH = '/src/Resources/24-month-fees.txt';
    const EXCEPTION_MESSAGE = 'incorrect formatting';

    /**
     * Read fee rules from file
     *
     * @param int $loadTerm
     *
     * @return FeeRule[] array of fee rules with loan amount as keys
     */
    public function getFeeRules(int $loanTerm): array
    {
        $return = [];
        foreach ($this->readFeeRulesFile($loanTerm) as $line) {
            $feeRule = $this->formatFeeRule($line);
            $return[$feeRule->centesimalAmount()] = $feeRule;
        }
        ksort($return);

        return $return;
    }

    /**
     * Read file containing fee rules
     *
     * @param int $loadTerm
     *
     * @return array lines from pre-formatted  file
     */
    protected function readFeeRulesFile(int $loanTerm): array
    {
        $filePath = match ($loanTerm) {
            12 => self::TWELVE_MONTH_FEES_PATH,
            24 => self::TWENTY_FOUR_MONTH_FEES_PATH
        };
        return file(getcwd() . $filePath);
    }

    /**
     * Create FeeRule object from a line
     *
     * @param string $line
     *
     * @return FeeRule
     */
    protected function formatFeeRule(string $line): FeeRule
    {
        $line = str_replace(' ', '', $line);

        //todo maybe loop through currency codes in a separate function ?
        $line = str_replace('PLN', '', $line);
        $feeRule = explode(self::FEE_SEPARATOR, $line);
        if (sizeof($feeRule) <= 1 || sizeof($feeRule) >= 3) {
            throw new Exception(self::EXCEPTION_MESSAGE);
        }
        $mappedFeeRule = array_map('intval', $feeRule);

        return new FeeRule($mappedFeeRule[0], $mappedFeeRule[1]);
    }
}
