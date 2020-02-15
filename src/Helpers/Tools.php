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
     * @since  v1.0
     * @throws ParamOtoranException When called number is lower than 1.
     * @return string
     */
    public static function convNumberToRef(int $number) : string
    {

        // Throw.
        if ($number < 1 || $number > 702) {
            throw (new ParamOtoranException('cellNumber', '>=1 && <=702', (string) $number))
                ->addHint('You\'re trying to reach to cell which has wrong number. It has to be more than zero and less then 702 (ZZ).');
        }

        // Lvd.
        $letters = '';

        // Prepare for looping.
        if ($number > 26) {

            $upperPart = (int) floor($number / 26);
            $number   -= ( $upperPart * 26 );

            // Correction for transition places (between *Z and *A).
            if ($number === 0) {
                $number = 26;
                --$upperPart;
            }

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
     * Converts integer row and col numbers into Excel style cell ref (eg. `A1`x).
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
     * Converts cell ref (eg. `A1`) into array with integer row [0] and col [1] numbers.
     *
     * @param string $ref Cell ref, eg. `A1`.
     *
     * @since  v1.0
     * @return array
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

    /**
     * Converts cells ref (eg. `A2:B4`) into explanatory array.
     *
     * ## Return format for `A2:B4`
     * ```
     * [
     *     'firstCell' => [ 2, 1 ],
     *     'lastCell'  => [ 4, 2 ],
     * ]
     * ```
     *
     * ## Return format for `B4`
     * ```
     * [
     *     'firstCell' => [ 4, 2 ],
     *     'lastCell'  => [ 4, 2 ],
     * ]
     * ```
     *
     * @param string $refs Cells ref (eg. `A2:B4` or just `B34`).
     *
     * @since  v1.0
     * @return array
     */
    public static function convCellsRefToNumbers(string $refs) : array
    {

        // If it is range?
        if (is_int(strpos($refs, ':')) === true) {

            list($firstCell, $lastCell) = explode(':', $refs);

            return [
                'firstCell' => self::convCellRefToNumbers($firstCell),
                'lastCell'  => self::convCellRefToNumbers($lastCell),
            ];
        }

        // If it is just one cell.
        return [
            'firstCell' => self::convCellRefToNumbers($refs),
            'lastCell'  => self::convCellRefToNumbers($refs),
        ];
    }

    /**
     * Convers sheet ref (eg. `Sheet1!A3:B6`) into array.
     *
     * ## Return format for `Sheet1!A3:B6`
     * ```
     * [
     *     'sheetName' => 'Sheet1',
     *     'firstCell' => [ 3, 1 ],
     *     'lastCell'  => [ 6, 2 ],
     * ]
     * ```
     *
     * @param string $ref Sheet ref (eg. `Sheet1!A3:B6`).
     *
     * @since  v1.0
     * @return array
     */
    public static function explainSheetRef(string $ref) : array
    {

        // Take sheet name and cells ref apart.
        list($sheetName, $cellsRef) = explode('!', $ref);

        // Add sheet name to result.
        $result = [
            'sheetName' => trim($sheetName, '\''),
        ];

        // Add refs to result.
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
