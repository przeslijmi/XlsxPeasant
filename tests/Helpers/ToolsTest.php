<?php declare(strict_types=1);

namespace Przeslijmi\Sirouter;

use PHPUnit\Framework\TestCase;
use Przeslijmi\XlsxPeasant\Helpers\Tools;

/**
 * Methods for testing XLSxPeasant Tools.
 */
final class ToolsTest extends TestCase
{

    /**
     * Data provider for vector convertions tests.
     *
     * @return array
     */
    public function vectorConvertionTestsDataProvider() : array
    {

        return [
            [ 1, 'A' ],
            [ 15, 'O' ],
            [ 26, 'Z' ],
            [ 27, 'AA' ],
            [ 700, 'ZX' ],
        ];
    }

    /**
     * Data provider for cell ref convertions tests.
     *
     * @return array
     */
    public function cellConvertionTestsDataProvider() : array
    {

        return [
            [ 1, 8, 'A8' ],
            [ 15, 21, 'O21' ],
            [ 26, 88, 'Z88' ],
            [ 27, 199, 'AA199' ],
            [ 700, 22, 'ZX22' ],
        ];
    }

    /**
     * Data provider for cells ref convertions tests.
     *
     * @return array
     */
    public function cellsConvertionTestsDataProvider() : array
    {

        return [
            [ 'A8:B3', 8, 1, 3, 2 ],
            [ 'O21:O21', 21, 15, 21, 15 ],
            [ 'Z88', 88, 26, 88, 26 ],
            [ 'AA199:ZX22', 199, 27, 22, 700 ],
            [ 'ZX22:ZY22', 22, 700, 22, 701 ],
        ];
    }

    /**
     * Test if convertion from number to ref and opposit work.
     *
     * @return void
     *
     * @dataProvider vectorConvertionTestsDataProvider
     */
    public function testVectorsConvertions(int $number, string $ref) : void
    {

        // Tests.
        $this->assertEquals($ref, Tools::convNumberToRef($number));
        $this->assertEquals($number, Tools::convRefToNumber($ref));
    }

    /**
     * Test if confertion of cell refs are working.
     *
     * @return void
     *
     * @dataProvider cellConvertionTestsDataProvider
     */
    public function testCellConvertions(int $col, int $row, string $ref) : void
    {

        // Tests.
        $this->assertEquals($ref, Tools::convToCellRef($row, $col));
        $this->assertEquals([ $row, $col ], Tools::convCellRefToNumbers($ref));
    }

    /**
     * Test if confertion of cell refs are working.
     *
     * @return void
     *
     * @dataProvider cellsConvertionTestsDataProvider
     */
    public function testCellsConvertions(
        string $cellsRef,
        int $firstCol,
        int $firstRow,
        int $lastCol,
        int $lastRow
    ) : void {

        // Lvd.
        $expect = [
            'firstCell' => [ $firstCol, $firstRow ],
            'lastCell' => [ $lastCol, $lastRow ],
        ];

        // Tests.
        $this->assertEquals($expect, Tools::convCellsRefToNumbers($cellsRef));
    }
}
