<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Tests\Unit\Model;

use Exception;
use Generator;
use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Model\LoanProposal;

final class LoanProposalTest extends TestCase
{
    /**
     * @dataProvider gettersProvider
     */
    public function testGetters(int $term, float $amount, int $expectedCentesimal)
    {
        $loanProposal = new LoanProposal($term, $amount);
        $this->assertEquals($expectedCentesimal, $loanProposal->centesimalAmount());
    }

    static function gettersProvider()
    {
        yield [12, 1500.00, 150000];
        yield [12, 1500.30, 150030];
        yield [12, 1500.33, 150033];
    }

    /**
     * @dataProvider validateProvider
     */
    public function testValidate(int $term, float $amount, string $expectedExceptionMessage)
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($expectedExceptionMessage);
        new LoanProposal($term, $amount);
    }

    public static function validateProvider(): Generator
    {
        yield [
            12,
            500,
            LoanProposal::INCORRECT_LOAN_AMOUNT
        ];
        yield [
            24,
            20500,
            LoanProposal::INCORRECT_LOAN_AMOUNT
        ];
        yield [
            12,
            -500,
            LoanProposal::INCORRECT_LOAN_AMOUNT
        ];
        yield [
            24,
            1500.123,
            LoanProposal::INCORRECT_LOAN_FORMAT
        ];
        yield [
            10,
            1000,
            LoanProposal::INCORRECT_LOAN_TERM
        ];
        yield [
            -12,
            1000,
            LoanProposal::INCORRECT_LOAN_TERM
        ];
        yield [
            25,
            1000,
            LoanProposal::INCORRECT_LOAN_TERM
        ];
    }
}
