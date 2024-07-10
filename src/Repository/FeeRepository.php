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
    const FORMATTING_EXCEPTION = 'incorrect formatting';
    const TERM_EXCEPTION = 'incorrect term';
    const NO_FILE_EXCEPTION = 'no file was found';

    /**
     * Read fee rules from file
     *
     * @param int $loanTerm
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
     * @param int $loanTerm
     *
     * @return array<int, string> lines from pre-formatted file
     */
    protected function readFeeRulesFile(int $loanTerm): array
    {
        $filePath = match ($loanTerm) {
            12 => self::TWELVE_MONTH_FEES_PATH,
            24 => self::TWENTY_FOUR_MONTH_FEES_PATH,
            default => throw new Exception(self::TERM_EXCEPTION)
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
            throw new Exception(self::FORMATTING_EXCEPTION);
        }
        $mappedFeeRule = array_map('intval', $feeRule);

        return new FeeRule($mappedFeeRule[0], $mappedFeeRule[1]);
    }
}
