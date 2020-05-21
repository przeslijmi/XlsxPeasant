<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Sexceptions\Exceptions\ObjectDonoexException;
use Przeslijmi\XlsxPeasant\Reader;
use Przeslijmi\XlsxPeasant\Reader\XmlFile\XlSharedStrings;
use Przeslijmi\XlsxPeasant\Reader\XmlFile\XlWorkbook;
use Przeslijmi\XlsxPeasant\Xlsx;

/**
 * Methods for testing reading of XLSX files.
 */
final class ReaderTest extends TestCase
{

    /**
     * Provider of corrupted XLSX uris.
     *
     * @return array[]
     */
    public function corruptedUrisProvider() : array
    {

        $dir = dirname(__DIR__) . '/examples/';

        return [
            [ $dir . 'ReaderTestCorrupted1.xlsx' ],
            [ $dir . 'ReaderTestCorrupted2.xlsx' ],
            [ $dir . 'ReaderTestCorrupted3.xlsx' ],
            [ $dir . 'ReaderTestCorrupted4.xlsx' ],
            [ $dir . 'ReaderTestCorrupted5.xlsx' ],
            [ $dir . 'ReaderTestCorrupted6.xlsx' ],
            [ $dir . 'ReaderTestCorrupted7.xlsx' ],
            [ $dir . 'ReaderTestCorrupted8.xlsx' ],
        ];
    }

    /**
     * Tests if reading XLSX works properly.
     *
     * @return void
     */
    public function testIfReadingWorks() : void
    {

        // Read Xlsx.
        $xlsx = new Reader(dirname(__DIR__) . '/examples/ReaderTest.xlsx');
        $book = $xlsx->readIn()->getBook();

        // Get Sheets.
        $sheet1 = $book->getSheetByName('Sheet1');
        $sheet2 = $book->getSheetByName('Sheet2');

        // Get Table.
        $tableData = $book->getTableByName('Table1')->getData();

        // Define proper data.
        $properData = [
            [
                'Column1' => 'Data.1.1.',
                'Column2' => 'Data.1.2.',
            ],
            [
                'Column1' => 'Data.2.1.',
                'Column2' => 'Data.2.2.',
            ],
        ];

        // Test.
        $this->assertEquals($properData, $tableData);
        $this->assertEquals('Sheet1', $sheet1->getName());
        $this->assertEquals('Sheet2', $sheet2->getName());
        $this->assertEquals('Free cell B1', $sheet2->getCell(1, 2)->getSimpleValue());
        $this->assertEquals('Free cell C2', $sheet2->getCell(2, 3)->getSimpleValue());
        $this->assertEquals('Free cell B3', $sheet2->getCell(3, 2)->getSimpleValue());
        $this->assertEquals('Free cell B4 and bolded', $sheet2->getCell(4, 2)->getSimpleValue());
        $this->assertEquals('5', $sheet2->getCell(3, 4)->getSimpleValue());
        $this->assertEquals(100, $xlsx->getStopReadingOnEmptyRows());
        $this->assertInstanceOf(XlSharedStrings::class, $xlsx->getXlSharedStrings());
        $this->assertInstanceOf(XlWorkbook::class, $xlsx->getXlWorkbook());
        $this->assertInstanceOf(Xlsx::class, $xlsx->getXlsx());

        // Get XlTable.
        $xlTable = $xlsx->getXlTables()[0];

        // Test.
        $this->assertEquals('Column1', $xlTable->getColumn(1));

        // Test if getting nonexisting column throws.
        try {
            $xlTable->getColumn(999);
        } catch (ObjectDonoexException $exc) {
            $this->assertTrue(true);
        }

        // Get XlWorksheet.
        $xlWorksheet = $xlsx->getXlWorksheets()[0];

        // Test.
        $this->assertEquals(1, $xlWorksheet->getNumber());
        $this->assertEquals(1, $xlWorksheet->getId());
        $this->assertEquals('Sheet1', $xlWorksheet->getName());
        $this->assertEquals('A1:B3', $xlWorksheet->getDimensionRef());

        // Get XlWorkbook.
        $xlWorkbook = $xlsx->getXlWorkbook();

        // Test.
        try {
            $xlWorkbook->getSheetName(999);
        } catch (ObjectDonoexException $exc) {
            $this->assertTrue(true);
        }
        try {
            $xlWorkbook->getSheetId(999);
        } catch (ObjectDonoexException $exc) {
            $this->assertTrue(true);
        }

        // Change settings.
        $xlsx->setStopReadingOnEmptyRows(-1);

        // Test.
        $this->assertEquals(-1, $xlsx->getStopReadingOnEmptyRows());
    }

    /**
     * Tests if reading nonexisting XLSX throws.
     *
     * @return void
     */
    public function testIfReadingNonexistingXlsxThrows() : void
    {

        // Expect.
        $this->expectException(ClassFopException::class);

        // Read Xlsx.
        $xlsx = new Reader(dirname(__DIR__) . '/examples/NonExistingReaderTest.xlsx');
    }

    /**
     * Tests if reading corrupted 1 XLSX throws.
     *
     * @param string $fileUri Uri of file to be tested.
     *
     * @return void
     *
     * @dataProvider corruptedUrisProvider
     */
    public function testIfReadingCorruptedXlsxThrows(string $fileUri) : void
    {

        // Expect.
        $this->expectException(ClassFopException::class);

        // Read Xlsx.
        $xlsx = new Reader($fileUri);
        $xlsx->readIn()->getBook();
    }

    /**
     * Tests if avoiding reading throws 1.
     *
     * @return void
     */
    public function testIfAvoidingReadinginXlsxThrows1() : void
    {

        // Expect.
        $this->expectException(ObjectDonoexException::class);

        // Read Xlsx.
        $xlsx = new Reader(dirname(__DIR__) . '/examples/ReaderTest.xlsx');

        // Get what is not present.
        $xlsx->getXlSharedStrings();
    }

    /**
     * Tests if avoiding reading throws 2.
     *
     * @return void
     */
    public function testIfAvoidingReadinginXlsxThrows2() : void
    {

        // Expect.
        $this->expectException(ObjectDonoexException::class);

        // Read Xlsx.
        $xlsx = new Reader(dirname(__DIR__) . '/examples/ReaderTest.xlsx');

        // Get what is not present.
        $xlsx->getXlWorkbook();
    }

    /**
     * Tests if reading proper XLSX with null cell value works.
     *
     * @return void
     */
    public function testIfReadingNullCellValuesWorks() : void
    {

        // Read Xlsx.
        $xlsx  = new Reader(dirname(__DIR__) . '/examples/ReaderTestNullValue.xlsx');
        $book  = $xlsx->readIn()->getBook();
        $sheet = $book->getSheetByName('Sheet2');

        // Get XlWorksheet (to call directly).
        $xlWorksheet = $xlsx->getXlWorksheets()[1];

        // Test.
        $this->assertEquals(null, $xlWorksheet->getCellValue(3, 4));
        $this->assertEquals(null, $xlWorksheet->getCellValue(10, 10));
        $this->assertEquals(null, $xlWorksheet->getCellValue(3, 10));
    }

    /**
     * Test if setting to ignore empty lines works.
     *
     * @return void
     */
    public function testIfIgnoringManyEmptyLinesWorks() : void
    {

        // Read Xlsx.
        $xlsx = new Reader(dirname(__DIR__) . '/examples/ReaderTestManyEmptyRows.xlsx');
        $xlsx->setStopReadingOnEmptyRows(3);

        // Read Xlsx.
        $book        = $xlsx->readIn()->getBook();
        $sheet       = $book->getSheetByName('Sheet1');
        $xlWorksheet = $xlsx->getXlWorksheets()[0];

        // Test.
        $this->assertEquals('Will read this', $sheet->getCell(1, 2)->getSimpleValue());
        $this->assertEquals('Will read this', $xlWorksheet->getCellValue(1, 2));
        $this->assertEquals('See below!', $sheet->getCell(2, 2)->getSimpleValue());
        $this->assertEquals('See below!', $xlWorksheet->getCellValue(2, 2));

        // This will not be read because of limit set to 100.
        $this->assertEquals(null, $sheet->getCell(7, 2)->getSimpleValue());
        $this->assertEquals('Won\'t read that!', $xlWorksheet->getCellValue(7, 2));
    }
}
