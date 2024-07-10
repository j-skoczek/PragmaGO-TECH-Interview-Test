<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Tests\Unit\Service;

use Exception;
use Generator;
use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Service\Math;

final class MathTest extends TestCase
{
    /**
     * @dataProvider interpolateProvider
     */
    public function testInterpolate(int $search, int $x1, int $y1, int $x2, int $y2, int $expected)
    {
        $mathService = new Math();
        $result = $mathService->interpolate($search, $x1, $y1, $x2, $y2);
        $this->assertEquals($expected, $result);
    }

    public static function interpolateProvider(): Generator
    {
        yield [19, 14, 400, 20, 220, 250];
        yield [14, 11, 100, 18, 65, 85];
        yield [14, 11, 101, 18, 65, 86];
    }

    /**
     * @dataProvider findClosestValuesTooSmallHaystackProvider
     */
    public function testFindClosestValuesTooSmallHaystack(float $search, array $haystack)
    {
        $mathService = new Math();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(Math::HAYSTACK_TOO_SMALL);
        $mathService->findClosestValues($search, $haystack);
    }

    public static function findClosestValuesTooSmallHaystackProvider(): Generator
    {
        yield [1, []];
        yield [1, [null]];
        yield [1, [1]];
    }

    /**
     * @dataProvider findClosestValuesProvider
     */
    public function testFindClosestValues(float $search, array $haystack, $expectedResult)
    {
        $mathService = new Math();
        $result = $mathService->findClosestValues($search, $haystack);
        $this->assertEquals($result, $expectedResult);
    }

    public static function findClosestValuesProvider(): Generator
    {
        yield [2, [2, 3], [2, 3]];
        yield [3, [1, 3, 5], [3, 1]];
        yield [1, [1, 2, 3], [1, 2]];
    }
}
