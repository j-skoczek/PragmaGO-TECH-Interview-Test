<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Tests\Integration;

use Generator;
use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Controller\FeeCalculator;
use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\Repository\FeeRepository;
use PragmaGoTech\Interview\Service\FeeCalculator as FeeCalculatorService;
use PragmaGoTech\Interview\Service\Math;

final class FeeCalculatorTest extends TestCase
{
    protected function getCalculator(int $term, float $amount): FeeCalculator
    {
        $loanProposal = new LoanProposal($term, $amount);
        $feeRepository = new FeeRepository();
        $mathService = new Math();
        $feeCalculatorService = new FeeCalculatorService($mathService);

        return new FeeCalculator($loanProposal, $feeRepository, $feeCalculatorService);
    }

    /**
     * @dataProvider loanProposalProvider
     * @coversNothing
     */
    public function testCalculate(int $term, float $amount, float $expectedFee)
    {
        $calculator = $this->getCalculator($term, $amount);
        $actualFee = $calculator->calculateFee();
        $this->assertEquals($expectedFee, $actualFee);
    }

    public static function loanProposalProvider(): Generator
    {
        yield [
            'term' => 24,
            'amount' => 11500,
            'expectedFee' => 460
        ];
        yield [
            'term' => 12,
            'amount' => 19250,
            'expectedFee' => 385
        ];
    }
}
