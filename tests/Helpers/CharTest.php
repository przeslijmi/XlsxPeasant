<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant;

use PHPUnit\Framework\TestCase;
use Przeslijmi\XlsxPeasant\Exceptions\UnservedUnicodeException;
use Przeslijmi\XlsxPeasant\Helpers\Char;

/**
 * Methods for testing XLSxPeasant Char class.
 */
final class CharTest extends TestCase
{

    /**
     * Data provider for dec and hex numbers.
     *
     * @return array
     */
    public function hexToDexProvider() : array
    {

        $result = [];

        for ($i = 1; $i <= 16; ++$i) {
            $result[] = [ ( $i * 60 ), dechex(( $i * 60 )) ];
        }

        return $result;
    }

    /**
     * Tests if byDec any byHex are returning identical results.
     *
     * @param integer $decimal Decimal value.
     * @param string  $hex     Hex value.
     *
     * @return void
     *
     * @dataProvider hexToDexProvider
     */
    public function testIfByHexWorks(int $decimal, string $hex) : void
    {

        $this->assertEquals(Char::byDec($decimal), Char::byHex($hex));
    }

    /**
     * Tests if byDec any byCode are returning identical results.
     *
     * @param integer $decimal Decimal value.
     * @param string  $hex     Hex value.
     *
     * @return void
     *
     * @dataProvider hexToDexProvider
     */
    public function testIfByCodeWorks(int $decimal, string $hex) : void
    {

        // Lvd.
        $code = 'U+' . str_pad($hex, 4, '0', STR_PAD_LEFT);

        $this->assertEquals(Char::byDec($decimal), Char::byCode($code));
    }

    /**
     * Test if getting above limit throws.
     *
     * @return void
     */
    public function testIfOtoranUnicodeThrowsOnTooMuch() : void
    {

        $this->expectException(UnservedUnicodeException::class);

        Char::byDec(1024);
    }

    /**
     * Test if getting below limit throws.
     *
     * @return void
     */
    public function testIfOtoranUnicodeThrowsOnTooLittle() : void
    {

        $this->expectException(UnservedUnicodeException::class);

        Char::byDec(0);
    }
}
