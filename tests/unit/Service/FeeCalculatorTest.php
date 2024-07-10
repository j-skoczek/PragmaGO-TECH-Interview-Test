<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Tests\Unit\Service;

use Generator;
use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Model\FeeRule;
use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\Service\FeeCalculator;
use PragmaGoTech\Interview\Service\Math;

final class FeeCalculatorTest extends TestCase
{
    /**
     * @param int $interpolationResult
     * @param int[] $closestValues
     * 
     * @return FeeCalculator
     */
    protected function getCalculator(int $interpolationResult, array $closestValues): FeeCalculator
    {
        $mathService = $this->createMock(Math::class);
        $mathService->method('interpolate')->willReturn($interpolationResult);
        $mathService->method('findClosestValues')->willReturn($closestValues);

        return new FeeCalculator($mathService);
    }

    /**
     * @dataProvider calculateFeeProvider
     */
    public function testCalculateFee(
        LoanProposal $loanProposal,
        array $feeRules,
        int $interpolationResult,
        array $closestValues,
        int $expectedFee
    ) {
        $calculator = $this->getCalculator($interpolationResult, $closestValues);
        $actualFee = $calculator->calculateFee($loanProposal, $feeRules);
        $this->assertEquals($expectedFee, $actualFee);
    }

    public static function calculateFeeProvider(): Generator
    {
        yield [
            'loanProposal' => new LoanProposal(12, 2000),
            'feeRules' => [
                100000 => new FeeRule(1000, 50),
                300000 => new FeeRule(3000, 150),
            ],
            'interpolationResult' => 10000,
            'closestValues' => [100000, 300000],
            'expectedFee' => 100
        ];
        yield [
            'loanProposal' => new LoanProposal(12, 2000),
            'feeRules' => [
                100000 => new FeeRule(1000, 50),
                300000 => new FeeRule(3000, 150),
            ],
            'interpolationResult' => 10100,
            'closestValues' => [100000, 300000],
            'expectedFee' => 105
        ];
        yield [
            'loanProposal' => new LoanProposal(12, 2000),
            'feeRules' => [
                100000 => new FeeRule(1000, 50),
                300000 => new FeeRule(3000, 150),
            ],
            'interpolationResult' => 10001,
            'closestValues' => [100000, 300000],
            'expectedFee' => 105
        ];
    }
}
