<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Helpers;

use Przeslijmi\Sexceptions\Exceptions\ParamOtoranException;
use Przeslijmi\Sivalidator\RegEx;
use UUID;

/**
 * Set of static tools to use while creating XLSX.
 */
class Tools
{

    /**
     * Letters to use in convertions.
     *
     * @var char[]
     */
    private static $letters = [
        '', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
        'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
    ];

    /**
     * Convert number (1, 2, 3, ...) into XLSX ref (A, B, C, ...).
     *
     * ## Usage example
     * ```
     * Tools::convNumberToRef(1);
     * \\ will return A
     * Tools::convNumberToRef(15);
     * \\ will return O
     * Tools::convNumberToRef(26);
     * \\ will return Z
     * Tools::convNumberToRef(27);
     * \\ will return AA
     * Tools::convNumberToRef(700);
     * \\ will return ZX
     * ```
     *
     * @param integer $number Number to be converted (larger than zero).
     *
     * @todo   Output string longer than 2 letters is outside of XLSx limits.
     * @since  v1.0
     * @throws ParamOtoranException When called number is lower than 1.
     * @return string
     */
    public static function convNumberToRef(int $number) : string
    {

        // Throw.
        if ($number < 1) {
            throw (new ParamOtoranException('cellNumber', '>=1', (string) $number))
                ->addHint('You\'re trying to reach to cell which has wrong number. It has to be more than zero');
        }

        // Lvd.
        $letters = '';

        // Prepare for looping.
        if ($number > 26) {
            $upperPart = (int) floor($number / 26);
            $number   -= ( $upperPart * 26 );

            // Go in loop if higher than 26.
            $letters .= self::convNumberToRef($upperPart);
        }

        // Include lowest letter.
        $letters .= self::$letters[$number];

        return $letters;
    }

    /**
     * Convert XLSX ref (A, B, C, ...) into number (1, 2, 3, ...).
     *
     * ## Usage example
     * ```
     * Tools::convRefToNumber(A);
     * \\ will return 1
     * Tools::convRefToNumber(O);
     * \\ will return 15
     * Tools::convRefToNumber(Z);
     * \\ will return 26
     * Tools::convRefToNumber(AA);
     * \\ will return 27
     * Tools::convRefToNumber(ZX);
     * \\ will return 700
     * ```
     *
     * @param string $ref Reference to be converted (only [A-Z]+).
     *
     * @since  v1.0
     * @return integer
     */
    public static function convRefToNumber(string $ref) : int
    {

        // Check.
        RegEx::ifMatches($ref, '/^([A-Z]+)$/');

        // Lvd.
        $result = 0;
        $wages  = array_flip(self::$letters);

        // Get char[].
        $chars = str_split($ref);

        // This is important - it lowers from n ... 0 and is used to multiply
        // subsequent letters wages.
        $i = count($chars);

        // For every char in string.
        foreach ($chars as $char) {

            // Lower multiplier.
            --$i;

            // Count wage for this one char and include it in result.
            $result += ( $wages[$char] * max(( 26 * $i ), 1) );
        }

        return $result;
    }

    /**
     * Converts integer row and col numbers into Excel style cell ref (eg. A1).
     *
     * @param integer $row Row number starting from 1.
     * @param integer $col Col number starting from 1.
     *
     * @since  v1.0
     * @return string
     */
    public static function convToCellRef(int $row, int $col) : string
    {

        return self::convNumberToRef($col) . $row;
    }

    /**
     * Converts cell ref (eg. A1) into array with integer row and col numbers.
     *
     * @param string $cell Cel ref, eg. A1.
     *
     * @since  v1.0
     * @return string
     */
    public static function convCellRefToNumbers(string $ref) : array
    {

        // To be sure.
        $ref = strtoupper($ref);

        return [
            (int) preg_replace('/([^0-9])/', '', $ref),
            self::convRefToNumber(preg_replace('/([^A-Z])/', '', $ref))
        ];
    }

    public static function convCellsRefToNumbers(string $refs) : array
    {

        if (is_int(strpos($refs, ':')) === true) {

            list($firstCell, $lastCell) = explode(':', $refs);

            return [
                'firstCell' => self::convCellRefToNumbers($firstCell),
                'lastCell'  => self::convCellRefToNumbers($lastCell),
            ];
        }

        return [
            'firstCell' => self::convCellRefToNumbers($refs),
            'lastCell'  => self::convCellRefToNumbers($refs),
        ];
    }

    public static function explainSheetRef(string $ref) : array
    {

        list($sheetName, $cellsRef) = explode('!', $ref);

        $result = [
            'sheetName' => trim($sheetName, '\''),
        ];

        $result = array_merge($result, self::convCellsRefToNumbers($cellsRef));

        return $result;
    }

    /**
     * Creates standard UUID to use in XLSx files.
     *
     * @param boolean $wrapInCurlyBraces Optional, true. If set to true curly braces are added at outskirts.
     *
     * @since  v1.0
     * @return string
     */
    public static function createUuid(bool $wrapInCurlyBraces = true) : string
    {

        // Lvd.
        $result = '';

        // Add starting cb.
        if ($wrapInCurlyBraces === true) {
            $result .= '{';
        }

        // Add actual UUID.
        $result .= strtoupper(UUID::v4()->toString());

        // Add ending cb.
        if ($wrapInCurlyBraces === true) {
            $result .= '}';
        }

        return $result;
    }
}
