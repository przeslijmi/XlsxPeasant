<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Helpers;

use Exception;

/**
 * Encodes and decodes time from Excel epoch to current.
 */
class Date
{

    /**
     * Converts date from Excel integer into Y-m-d format.
     *
     * @param integer $excel Excel's date as integer.
     *
     * @since  v1.0
     * @return string
     */
    public static function decode(int $excel) : string
    {

        return date('Y-m-d', mktime(0, 0, 0, 1, ( -1 + $excel ), 1900));
    }
}
