<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Tests\Unit\Controller;

use Generator;
use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Controller\FeeCalculator;
use PragmaGoTech\Interview\Model\FeeRule;
use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\Repository\FeeRepository;
use PragmaGoTech\Interview\Service\FeeCalculator as FeeCalculatorService;

final class FeeCalculatorTest extends TestCase
{
    protected function getCalculator(int $term, float $amount, array $feeRules, float $calculatedFee): FeeCalculator
    {
        $loanProposal = new LoanProposal($term, $amount);

        $feeRepository = $this->createMock(FeeRepository::class);
        $feeRepository->method('getFeeRules')->willReturn($feeRules);

        $feeCalculatorService = $this->createMock(FeeCalculatorService::class);
        $feeCalculatorService->method('calculateFee')->willReturn($calculatedFee);

        return new FeeCalculator($loanProposal, $feeRepository, $feeCalculatorService);
    }

    /**
     * @dataProvider validateProvider
     */
    public function testValidate(int $term, float $amount, array $feeRules, float $calculatedFee, float $expectedFee)
    {
        $calculator = $this->getCalculator($term, $amount, $feeRules, $calculatedFee);
        $actualFee = $calculator->calculateFee();
        $this->assertEquals($expectedFee, $actualFee);
    }

    public static function validateProvider(): Generator
    {
        yield 'exact rule is found' => [
            12,
            1000,
            [1000 => new FeeRule(1000, 50)],
            0,
            50
        ];
        yield 'use FeeCalculatorService' => [
            12,
            1000,
            [0 => new FeeRule(0, 0)],
            150,
            150
        ];
    }
}
