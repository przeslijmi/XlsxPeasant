<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant;

use PHPUnit\Framework\TestCase;
use Przeslijmi\XlsxPeasant\Helpers\Date;

/**
 * Methods for testing XLSxPeasant Date class.
 */
final class DateTest extends TestCase
{

    /**
     * Data provider for dec and hex numbers.
     *
     * @return array
     */
    public function datesProvider() : array
    {

        return [
            [ -100,  '1899-09-21' ],
            [ 1,     '1899-12-31' ],
            [ 40000, '2009-07-06' ],
            [ 44271, '2021-03-16' ],
        ];
    }

    /**
     * Tests if byDec any byHex are returning identical results.
     *
     * @param integer $excelDate Date as Excel integer.
     * @param string  $ymdDate   Date in Y-m-d format.
     *
     * @return void
     *
     * @dataProvider datesProvider
     */
    public function testIfByHexWorks(int $excelDate, string $ymdDate) : void
    {

        $this->assertEquals($ymdDate, Date::decode($excelDate));
    }
}
