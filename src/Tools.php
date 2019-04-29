<?php declare(strict_types=1);

namespace Przeslijmi\XlsxGenerator;

/**
 * Set of static tools to use while creating XLSX.
 */
class Tools
{

    /**
     * Convert number (1, 2, 3, ...) into XLSX ref (A, B, C, ...).
     *
     * @param integer $number Number to be converted.
     *
     * @todo   Add min/max value.
     * @since  v1.0
     * @return string
     */
    public static function convNumberToRef(int $number) : string
    {

        // Lvd.
        $firstChar = '';
        $letters   = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
            'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
        ];

        // Prepare.
        --$number;

        // Extra for numbers higher or equal to 26.
        if ($number >= 26) {
            $first     = floor($number / 26);
            $firstChar = $letters[$first];
            $number    = ( $number - $first * 26 );
        }

        return $firstChar . $letters[$number];
    }
}
