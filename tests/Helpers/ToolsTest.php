<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant;

use PHPUnit\Framework\TestCase;
use Przeslijmi\XlsxPeasant\Helpers\Tools;
use Przeslijmi\Sexceptions\Exceptions\ParamOtoranException;

/**
 * Methods for testing XLSxPeasant Tools.
 */
final class ToolsTest extends TestCase
{

    /**
     * Data provider for vector convertions tests.
     *
     * @return $arrayName = array('' => , );
     */
    public function vectorConvertionTestsDataProvider() : array
    {

        return [
            [ 1, 'A' ],
            [ 15, 'O' ],
            [ 26, 'Z' ],
            [ 27, 'AA' ],
            [ 52, 'AZ' ],
            [ 53, 'BA' ],
            [ 78, 'BZ' ],
            [ 79, 'CA' ],
            [ 700, 'ZX' ],
            [ 702, 'ZZ' ],
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
     * @param integer $number Number to ba changed to letter (eg. 2).
     * @param string  $ref    Letter corresponding with this number (eg. B) - ie. expected.
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
     * @param integer $col Id of column of cell (starting from 1).
     * @param integer $row Id of row of cell (starting from 1).
     * @param string  $ref Id of row for first cell (starting from 1).
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
     * @param string  $cellsRef Ref of many cells (eg. A8:B3).
     * @param integer $firstCol Id of column for first cell (starting from 1).
     * @param integer $firstRow Id of row for first cell (starting from 1).
     * @param integer $lastCol  Id of column for last cell (starting from 1).
     * @param integer $lastRow  Id of row for last cell (starting from 1).
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

    /**
     * Test if sending negative column number throws.
     *
     * @return void
     */
    public function testIfSendingNegativeColumnThrows() : void
    {

        $this->expectException(ParamOtoranException::class);

        Tools::convNumberToRef(-1);
    }

    /**
     * Test if confertion of sheet ref is working.
     *
     * @param string  $cellsRef Ref of many cells (eg. A8:B3).
     * @param integer $firstCol Id of column for first cell (starting from 1).
     * @param integer $firstRow Id of row for first cell (starting from 1).
     * @param integer $lastCol  Id of column for last cell (starting from 1).
     * @param integer $lastRow  Id of row for last cell (starting from 1).
     *
     * @return void
     *
     * @dataProvider cellsConvertionTestsDataProvider
     */
    public function testSheetConvertions(
        string $cellsRef,
        int $firstCol,
        int $firstRow,
        int $lastCol,
        int $lastRow
    ) : void {

        // Lvd.
        $expect = [
            'sheetName' => 'Sheet1',
            'firstCell' => [ $firstCol, $firstRow ],
            'lastCell'  => [ $lastCol, $lastRow ],
        ];

        // Tests.
        $this->assertEquals($expect, Tools::explainSheetRef('Sheet1!' . $cellsRef));
    }

    /**
     * Test creation of UUIDs.
     *
     * @return void
     */
    public function testCreationOfUuid() : void
    {

        $this->assertEquals(38, mb_strlen(Tools::createUuid()));
        $this->assertEquals(38, mb_strlen(Tools::createUuid(true)));
        $this->assertEquals(36, mb_strlen(Tools::createUuid(false)));
        $this->assertEquals(36, mb_strlen(trim(Tools::createUuid(), '{}')));
    }
}
