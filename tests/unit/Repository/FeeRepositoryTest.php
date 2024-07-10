<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Tests\Unit\Repository;

use Exception;
use Generator;
use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Repository\FeeRepository;

final class FeeRepositoryTest extends TestCase
{
    public function setUp(): void
    {
        unlink(dirname(__FILE__) . FeeRepository::TWELVE_MONTH_FEES_PATH);
        unlink(dirname(__FILE__) . FeeRepository::TWENTY_FOUR_MONTH_FEES_PATH);

        file_put_contents(
            dirname(__FILE__) . FeeRepository::TWELVE_MONTH_FEES_PATH,
            '1000 PLN: 50 PLN
            2000 PLN: 60 PLN'
        );
        file_put_contents(
            dirname(__FILE__) . FeeRepository::TWENTY_FOUR_MONTH_FEES_PATH,
            '1200 PLN: 60 PLN
            1000 PLN: 70 PLN'
        );

        chdir(dirname(__FILE__));
    }

    public function tearDown(): void
    {
        unlink(dirname(__FILE__) . FeeRepository::TWELVE_MONTH_FEES_PATH);
        unlink(dirname(__FILE__) . FeeRepository::TWENTY_FOUR_MONTH_FEES_PATH);
    }

    /**
     * @dataProvider validateProvider
     *
     * @param int $term
     * @param int $centesimalAmount
     * @param float $expectedFee
     * @param int[] $expectedOrder
     */
    public function testGetFeeRules(int $term, int $centesimalAmount, float $expectedFee, array $expectedOrder): void
    {
        $feeRepository = new FeeRepository();
        $feeRules = $feeRepository->getFeeRules($term);
        $feeRule = $feeRules[$centesimalAmount];
        $this->assertEquals($expectedFee, $feeRule->fee());

        $arrayKeys = array_keys($feeRules);
        sort($arrayKeys);
        $this->assertEquals($expectedOrder, $arrayKeys);
    }

    public static function validateProvider(): Generator
    {
        yield [12, 100000, 50, [100000, 200000]];
        yield [12, 200000, 60, [100000, 200000]];
        yield [24, 100000, 70, [100000, 120000]];
        yield [24, 120000, 60, [100000, 120000]];
    }

    /**
     * @dataProvider getFeeRulesExceptionsGenerator
     */
    public function testGetFeeRulesExceptions(string $path, string $expectedException, int $term): void
    {
        file_put_contents(
            dirname(__FILE__) . $path,
            'test'
        );

        $feeRepository = new FeeRepository();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($expectedException);
        $feeRepository->getFeeRules($term);
    }

    public static function getFeeRulesExceptionsGenerator(): Generator
    {
        yield [
            FeeRepository::TWELVE_MONTH_FEES_PATH,
            FeeRepository::FORMATTING_EXCEPTION,
            12
        ];
        yield [
            FeeRepository::TWELVE_MONTH_FEES_PATH,
            FeeRepository::TERM_EXCEPTION,
            10
        ];
    }
}
