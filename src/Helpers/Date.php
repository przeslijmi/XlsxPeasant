<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Helpers;

use Exception;

/**
 * Encodes and decodes time from Excel epoch to current.
 */
class Date
{

    /**
     * Show char by decimal number (eg. 511 --> ǿ)
     *
     * @param integer $decUnicode Unicode Code Point in decimal value.
     *
     * @since  v1.0
     * @return char
     *
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity
     */
    public static function decode(int $excel) : string
    {

        return date('Y-m-d', mktime(0, 0, 0, 1, (-1 + $excel), 1900));
    }
}
