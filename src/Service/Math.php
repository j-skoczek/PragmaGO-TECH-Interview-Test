<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Service;

use Exception;

class Math
{
    const HAYSTACK_TOO_SMALL = 'haystack needs have at least two values';

    /**
     * Use linear interpolation to find a value for given search. Result is rounded up to the nearest int
     *
     * @param int $search number for which we're trying to find a value
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $y2
     *
     * @return int
     */
    public function interpolate(int $search, int $x1, int $y1, int $x2, int $y2): int
    {
        $return = $y1 + ($search - $x1) * ($y2 - $y1) / ($x2 - $x1);
        return (int)ceil($return);
    }

    /**
     * Find two values closest to search in an array. In case two values are equidistant the lower one is returned
     *
     * @param float $search
     * @param int[] $haystack array of ints
     *
     * @return int[] array with two closest values
     */
    public function findClosestValues(float $search, array $haystack): array
    {
        if (sizeof($haystack) < 2) {
            throw new Exception(self::HAYSTACK_TOO_SMALL);
        } elseif (sizeof($haystack) === 2) {
            return $haystack;
        }

        $return = [];

        //1st closest
        $closest = $this->getClosest($search, $haystack);
        $return[] = $closest;

        //2nd closest
        $reducedHaystack = array_diff($haystack, [$closest]);
        $return[] = $this->getClosest($search, $reducedHaystack);

        return $return;
    }

    /**
     * Find closest value in array for given search if two values are equidistant the lower one is returned
     *
     * @param float $search
     * @param int[] $arr
     *
     * @return int
     */
    protected function getClosest($search, $arr): int
    {
        $closest = null;
        foreach ($arr as $value) {
            if ($closest === null || abs($search - $closest) > abs($value - $search)) {
                $closest = $value;
            }
        }
        return $closest;
    }
}
