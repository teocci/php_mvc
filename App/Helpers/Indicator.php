<?php
/**
 * Created by teocci.
 *
 * Author: teocci@yandex.com on 2019-Jan-22
 */

namespace App\Helpers;


class Indicator
{
    /**
     * Returns a standardized int value based on the indicator name and value received
     *
     * @param string $name
     * @param int $value
     * @return int
     */
    public static function standardize($name, $value)
    {
        switch ($name) {
            case 'particles':
                return self::normalize($value, [5000, 10000, 25000]);
            case 'dioxide':
                return self::normalize($value, [6000, 13000, 26000]);
            case 'voc':
                return self::normalize($value, [13000, 26000, 40000]);
            case 'radon':
                return self::normalize($value, [2000, 10000, 26000]);
            default:
                return -1;
        }
    }

    /**
     * Normalizes values as int based on ranges
     *
     * @param int $value
     * @param array $ranges
     * @return int
     */
    public static function normalize($value, array $ranges)
    {
        if ($value < ($ranges[0] + 1)) {
            return 0;
        } elseif ($value > $ranges[0] && $value < ($ranges[1] + 1)) {
            return 1;
        } elseif ($value > $ranges[1] && $value < ($ranges[2] + 1)) {
            return 2;
        } elseif ($value > $ranges[2]) {
            return 3;
        } else {
            return -1;
        }
    }
}