<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Helpers;

use Przeslijmi\XlsxPeasant\Exceptions\UnservedUnicodeException;

/**
 * Spin-off tool to show Char of given Unicode Code Point (U+0000 to U+03FF).
 */
class Char
{

    /**
     * Show char by decimal number (eg. 511 --> ǿ) from range 1 ... 1023.
     *
     * @param integer $decUnicode Unicode Code Point in decimal value.
     *
     * @since  v1.0
     * @throws UnservedUnicodeException When call is above range 1 ... 1023.
     * @return char
     *
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity
     */
    public static function byDec(int $decUnicode) : string
    {

        if ($decUnicode > 0 && $decUnicode < hexdec('80')) {
            return chr($decUnicode);
        }

        if ($decUnicode >= hexdec('80') && $decUnicode < hexdec('C0')) {
            return chr(hexdec('C2')) . chr($decUnicode);
        }

        if ($decUnicode >= hexdec('C0') && $decUnicode < hexdec('100')) {
            return chr(hexdec('C3')) . chr(( hexdec('80') + $decUnicode - hexdec('C0') ));
        }

        if ($decUnicode >= hexdec('100') && $decUnicode < hexdec('140')) {
            return chr(hexdec('C4')) . chr(( hexdec('80') + $decUnicode - hexdec('100') ));
        }

        if ($decUnicode >= hexdec('140') && $decUnicode < hexdec('180')) {
            return chr(hexdec('C5')) . chr(( hexdec('80') + $decUnicode - hexdec('140') ));
        }

        if ($decUnicode >= hexdec('180') && $decUnicode < hexdec('1C0')) {
            return chr(hexdec('C6')) . chr(( hexdec('80') + $decUnicode - hexdec('180') ));
        }

        if ($decUnicode >= hexdec('1C0') && $decUnicode < hexdec('200')) {
            return chr(hexdec('C7')) . chr(( hexdec('80') + $decUnicode - hexdec('1C0') ));
        }

        if ($decUnicode >= hexdec('200') && $decUnicode < hexdec('240')) {
            return chr(hexdec('C8')) . chr(( hexdec('80') + $decUnicode - hexdec('200') ));
        }

        if ($decUnicode >= hexdec('240') && $decUnicode < hexdec('280')) {
            return chr(hexdec('C9')) . chr(( hexdec('80') + $decUnicode - hexdec('240') ));
        }

        if ($decUnicode >= hexdec('280') && $decUnicode < hexdec('2C0')) {
            return chr(hexdec('CA')) . chr(( hexdec('80') + $decUnicode - hexdec('280') ));
        }

        if ($decUnicode >= hexdec('2C0') && $decUnicode < hexdec('300')) {
            return chr(hexdec('CB')) . chr(( hexdec('80') + $decUnicode - hexdec('2C0') ));
        }

        if ($decUnicode >= hexdec('300') && $decUnicode < hexdec('340')) {
            return chr(hexdec('CC')) . chr(( hexdec('80') + $decUnicode - hexdec('300') ));
        }

        if ($decUnicode >= hexdec('340') && $decUnicode < hexdec('380')) {
            return chr(hexdec('CD')) . chr(( hexdec('80') + $decUnicode - hexdec('340') ));
        }

        if ($decUnicode >= hexdec('380') && $decUnicode < hexdec('3C0')) {
            return chr(hexdec('CE')) . chr(( hexdec('80') + $decUnicode - hexdec('380') ));
        }

        if ($decUnicode >= hexdec('3C0') && $decUnicode <= hexdec('3FF')) {
            return chr(hexdec('CF')) . chr(( hexdec('80') + $decUnicode - hexdec('3C0') ));
        }

        throw new UnservedUnicodeException($decUnicode);
    }

    /**
     * Show char by decimal number.
     *
     * @param string $hexUnicode Unicode Code Point in hev value (eg. 1FF --> ǿ).
     *
     * @since  v1.0
     * @return char
     */
    public static function byHex(string $hexUnicode) : string
    {

        return self::byDec(hexdec($hexUnicode));
    }

    /**
     * Show char by unicode.
     *
     * @param string $unicode Unicode Code Point as is (eg. U+01FF --> ǿ).
     *
     * @since  v1.0
     * @return char
     */
    public static function byCode(string $unicode) : string
    {

        return self::byDec(hexdec(substr($unicode, 2)));
    }
}
