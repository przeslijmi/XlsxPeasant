<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Sexceptions\Exceptions\ParamOtoranException;
use Przeslijmi\Sexceptions\Exceptions\ParamWrosynException;
use Przeslijmi\XlsxPeasant\Exceptions\CellMergeConflictException;
use Przeslijmi\XlsxPeasant\Exceptions\CellValueWrotypeException;
use Przeslijmi\XlsxPeasant\Exceptions\ColorFactoryFopException;
use Przeslijmi\XlsxPeasant\Exceptions\ColorNameOtosetException;
use Przeslijmi\XlsxPeasant\Exceptions\ColumnDonoexException;
use Przeslijmi\XlsxPeasant\Exceptions\ColumnIdOtoranException;
use Przeslijmi\XlsxPeasant\Exceptions\ColumnNameAlrexException;
use Przeslijmi\XlsxPeasant\Exceptions\ColumnNameWrosynException;
use Przeslijmi\XlsxPeasant\Exceptions\FillFactoryFopException;
use Przeslijmi\XlsxPeasant\Exceptions\FontFactoryFopException;
use Przeslijmi\XlsxPeasant\Exceptions\HorizontalAlignOtosetException;
use Przeslijmi\XlsxPeasant\Exceptions\LookingForSpareIdLoopOtoranException;
use Przeslijmi\XlsxPeasant\Exceptions\NoColumnsInTableException;
use Przeslijmi\XlsxPeasant\Exceptions\NoSheetsException;
use Przeslijmi\XlsxPeasant\Exceptions\RefWrosynException;
use Przeslijmi\XlsxPeasant\Exceptions\SetValueToMergedCellConflictException;
use Przeslijmi\XlsxPeasant\Exceptions\SheetDonoexException;
use Przeslijmi\XlsxPeasant\Exceptions\SheetIdOtoranException;
use Przeslijmi\XlsxPeasant\Exceptions\SheetNameAlrexException;
use Przeslijmi\XlsxPeasant\Exceptions\SheetNameWrosynException;
use Przeslijmi\XlsxPeasant\Exceptions\StyleLockedException;
use Przeslijmi\XlsxPeasant\Exceptions\TableChangeColumnForbiddenException;
use Przeslijmi\XlsxPeasant\Exceptions\TableCreationFopException;
use Przeslijmi\XlsxPeasant\Exceptions\TableDonoexException;
use Przeslijmi\XlsxPeasant\Exceptions\UnknownDefaultSettingException;
use Przeslijmi\XlsxPeasant\Exceptions\VerticalAlignOtosetException;
use Przeslijmi\XlsxPeasant\Exceptions\WrotypeDefaultsException;
use Przeslijmi\XlsxPeasant\Items\Book;
use Przeslijmi\XlsxPeasant\Items\Cell;
use Przeslijmi\XlsxPeasant\Items\Color;
use Przeslijmi\XlsxPeasant\Items\Column;
use Przeslijmi\XlsxPeasant\Items\Fill;
use Przeslijmi\XlsxPeasant\Items\Font;
use Przeslijmi\XlsxPeasant\Items\Sheet;
use Przeslijmi\XlsxPeasant\Items\Style;
use Przeslijmi\XlsxPeasant\Items\Table;
use Przeslijmi\XlsxPeasant\Xlsx;

/**
 * Methods for testing Items and children.
 */
final class ItemsTest extends TestCase
{

    /**
     * Tests if creating Sheet in XLSX works properly.
     *
     * @return void
     */
    public function testIfCreatingSheetWorks() : void
    {

        // Create Xlsx.
        $xlsx = new Xlsx();
        $book = $xlsx->getBook();

        // Try to get Sheets when no Sheets exists.
        try {
            $book->getSheets();
        } catch (NoSheetsException $exc) {
            $this->assertTrue(true);
        }

        // Add Sheet.
        $sheet = $xlsx->getBook()->addSheet('Items Test');

        // Test proper existence of Sheet.
        $this->assertInstanceOf(Sheet::class, $sheet);
        $this->assertInstanceOf(Book::class, $book);
        $this->assertEquals($sheet, $book->getSheets()[0]);
        $this->assertEquals(1, count($book->getSheets()));
        $this->assertEquals($sheet, $book->getSheet($sheet->getId()));
        $this->assertEquals($sheet, $book->getSheetByName($sheet->getName()));
        $this->assertTrue($book->hasSheetByName($sheet->getName()));
        $this->assertFalse($book->hasSheetByName('Nonexisting sheet'));
        $this->assertEquals($book, $sheet->getBook());
        $this->assertEquals(38, strlen($sheet->getUuid()));

        // Test if nonexisting Sheet ID throws.
        try {
            $book->getSheet(999);
        } catch (SheetDonoexException $exc) {
            $this->assertTrue(true);
        }

        // Test if nonexisting Sheet name throws.
        try {
            $book->getSheetByName('Wrong Name');
        } catch (SheetDonoexException $exc) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test if creating wrong Sheet throws.
     *
     * @return void
     */
    public function testIfCreatingSheetBadlyThrows() : void
    {

        // Create Xlsx.
        $xlsx = new Xlsx();

        // Try to create badly named Sheet.
        try {
            $xlsx->getBook()->addSheet('Wrong Name !@R%^#*)(@8v :::"');
        } catch (SheetNameWrosynException $exc) {
            $this->assertTrue(true);
        }

        // Try to create duplicated Sheet.
        try {
            $xlsx->getBook()->addSheet('Test');
            $xlsx->getBook()->addSheet('Test');
        } catch (SheetNameAlrexException $exc) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test if creating Sheet with wrong ID.
     *
     * @return void
     */
    public function testIfCreatingSheetWithWrongIdThrows() : void
    {

        // Create Xlsx.
        $xlsx = new Xlsx();

        // Create Sheet.
        try {
            $sheet = new Sheet($xlsx, 'TestName', -1);
        } catch (SheetIdOtoranException $exc) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test if creating Table in Sheet works.
     *
     * @return void
     */
    public function testIfCreatingTablesWorks() : void
    {

        // Create Xlsx.
        $xlsx  = new Xlsx();
        $book  = $xlsx->getBook();
        $sheet = $book->addSheet('Tables Test');

        // Test.
        $this->assertFalse($sheet->hasTables());

        // Create Table.
        $table = $sheet->addTable('Test', 1, 1);

        // Test.
        $this->assertInstanceOf(Table::class, $table);
        $this->assertEquals('Test', $table->getName());
        $this->assertEquals($table, $sheet->getTables()[0]);
        $this->assertEquals($table, $book->getTableByName('Test'));
        $this->assertTrue($book->hasTableByName('Test'));
        $this->assertFalse($book->hasTableByName('Test Wrong'));
        $this->assertTrue($sheet->hasTables());
        $this->assertTrue($book->hasTables());
        $this->assertTrue($book->hasTables($sheet));
        $this->assertEquals(38, strlen($table->getUuid()));

        // Create Table with identical name.
        try {
            $sheet->addTable('Test', 1, 1);
        } catch (TableCreationFopException $exc) {
            $this->assertTrue(true);
        }

        // Create Table with wrong syntax name.
        try {
            $sheet->addTable('Test  Test', 1, 1);
        } catch (TableCreationFopException $exc) {
            $this->assertTrue(true);
        }

        // Create Table with wrong Ref.
        try {
            $sheet->addTable('Wrong', -1, -1);
        } catch (TableCreationFopException $exc) {
            $this->assertTrue(true);
        }

        // Create Table with wrong Id.
        try {
            $sheet->addTable('WrongId', 1, 1, -1);
        } catch (TableCreationFopException $exc) {
            $this->assertTrue(true);
        }

        // Get nonexisting Table.
        try {
            $book->getTableByName('Test Nonexisting');
        } catch (TableDonoexException $exc) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test if defining wrong defaults throws.
     *
     * @return void
     */
    public function testIfDefiningWrongDefaultsThrows() : void
    {

        // Lvd.
        $xlsx = new Xlsx();

        // Test 1.
        try {
            $xlsx->setDefault('unknownDefinition', true);
        } catch (UnknownDefaultSettingException $exc) {
            $this->assertTrue(true);
        }

        // Test 2.
        try {
            $xlsx->setDefault('fontSize', false);
        } catch (WrotypeDefaultsException $exc) {
            $this->assertTrue(true);
        }

        // Test 3.
        try {
            $xlsx->setDefault('fontColor', false);
        } catch (WrotypeDefaultsException $exc) {
            $this->assertTrue(true);
        }

        // Test 4.
        try {
            $xlsx->setDefault('fontName', false);
        } catch (WrotypeDefaultsException $exc) {
            $this->assertTrue(true);
        }

        // Test 5.
        try {
            $xlsx->setDefault('fontColor', $this);
        } catch (WrotypeDefaultsException $exc) {
            $this->assertTrue(true);
        }

        // Test 6.
        try {
            $xlsx->getDefault('unknownDefinition', true);
        } catch (UnknownDefaultSettingException $exc) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test if defining Table in Sheet works.
     *
     * @return void
     */
    public function testIfDefiningTablesWorks() : void
    {

        // Lvd.
        $data = [
            0 => [
                'Column2' => 'test for row 0 column 2',
                'Column3' => 'test for row 0 column 3',
            ],
            1 => [
                'Column2' => 'test for row 1 column 2',
                'Column3' => 'test for row 1 column 3',
            ],
            2 => [
                'Column2' => 'test for row 2 column 2',
                'Column3' => 'test for row 2 column 3',
            ],
            3 => [
                'Column2' => 'test for row 3 column 2',
                'Column3' => 'test for row 3 column 3',
            ],
        ];

        // Create Xlsx.
        $xlsx  = new Xlsx();
        $book  = $xlsx->getBook();
        $sheet = $book->addSheet('Tables Test');
        $table = $sheet->addTable('Table.Test', 1, 1);

        // Test if no Columns there are.
        try {
            $table->getColumns();
        } catch (NoColumnsInTableException $exc) {
            $this->assertTrue(true);
        }

        // Add columns.
        $column = $table->addColumn('Column1', 1);
        $table->addColumns([ 'Column2', 'Column3' ]);

        // Test.
        $this->assertInstanceOf(Column::class, $column);
        $this->assertEquals($column, $table->getColumnByName('Column1'));
        $this->assertEquals($column, $table->getColumnById(1));
        $this->assertEquals(3, $table->countColumns());
        $this->assertEquals(38, strlen($column->getUuid()));

        // Test if getting nonexisting Column throws.
        try {
            $table->getColumnByName('Column4');
        } catch (ColumnDonoexException $exc) {
            $this->assertTrue(true);
        }

        // Test if getting nonexisting Column throws.
        try {
            $table->getColumnById(999);
        } catch (ColumnDonoexException $exc) {
            $this->assertTrue(true);
        }

        // Create Column with wrong Id.
        try {
            $table->addColumn('WrongId', -1);
        } catch (ColumnIdOtoranException $exc) {
            $this->assertTrue(true);
        }

        // Create Column with wrong name.
        try {
            $table->addColumn(str_repeat('Very Long Name ', 50));
        } catch (ColumnNameWrosynException $exc) {
            $this->assertTrue(true);
        }

        // Create Column with duplicated name.
        try {
            $table->addColumn('Column1');
        } catch (ColumnNameAlrexException $exc) {
            $this->assertTrue(true);
        }

        // Add data.
        $table->setData([ $data[0] ]);
        $table->setData([ $data[1], $data[2] ]);
        $table->addData([ $data[3] ]);

        // Test.
        $this->assertEquals([ $data[1], $data[2], $data[3] ], $table->getData());
    }

    /**
     * Test if adding Columns to Table with rows works.
     *
     * @return void
     */
    public function testIfAddingTableColumnsAfterRowsThrows() : void
    {

        // Create Xlsx.
        $xlsx  = new Xlsx();
        $book  = $xlsx->getBook();
        $sheet = $book->addSheet('Tables Test');
        $table = $sheet->addTable('Table.Test', 1, 1);

        // Add column and data.
        $table->addColumn('Column1', 1);
        $table->addData([
            [
                'Column1' => 'test',
            ],
            [
                'Column1' => 'test',
            ],
        ]);

        // Try to add new column.
        try {
            $table->addColumn('Column2', 2);
        } catch (TableChangeColumnForbiddenException $exc) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test if adding data to nonexisting Column throws.
     *
     * @return void
     */
    public function testIfAddingDataToNonexistingColumnThrows() : void
    {

        // Create Xlsx.
        $xlsx  = new Xlsx();
        $book  = $xlsx->getBook();
        $sheet = $book->addSheet('Tables Test');
        $table = $sheet->addTable('Table.Test', 1, 1);

        // Add column.
        $table->addColumn('Column1', 1);

        // Try to add data to nonexisting column.
        try {
            $table->addData([
                [
                    'Column1' => 'test',
                    'Column2' => 'test',
                ],
                [
                    'Column1' => 'test',
                    'Column2' => 'test',
                ],
            ]);
        } catch (ColumnDonoexException $exc) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test if defining Cells in Sheet works.
     *
     * @return void
     */
    public function testIfCreatingCellsWorks() : void
    {

        // Create Xlsx.
        $xlsx  = new Xlsx();
        $book  = $xlsx->getBook();
        $sheet = $book->addSheet('Tables Test');

        // Test if getting nonexisting Column throws.
        try {
            $sheet->getCell(0, 0);
        } catch (RefWrosynException $exc) {
            $this->assertTrue(true);
        }

        // Test.
        $this->assertEquals('A1', $sheet->getFirstCellRef());
        $this->assertEquals('A1', $sheet->getLastCellRef());

        // Add cell 1.
        $cell1 = $sheet->getCell(1, 1);
        $cell1->setValue('aaa');
        $cell1->setColWidth(10.0);
        $cell1->setRowHeight(20.0);

        // Add cell 2.
        $cell2 = $sheet->getCell(2, 3);
        $cell2->setValueParts([ [ 'aaa' ], [ 'bbb' ] ]);
        $cell2->setColWidth(null);
        $cell2->setRowHeight(null);

        // Add cell 3.
        $cell3 = $sheet->getCell(3, 1);

        // Test.
        $this->assertInstanceOf(Cell::class, $cell1);
        $this->assertEquals('aaa', $cell1->getValue()[0]->getContentsAsScalar());
        $this->assertEquals('bbb', $cell2->getValue()[1]->getContentsAsScalar());
        $this->assertEquals('aaa', $cell1->getSimpleValue());
        $this->assertEquals('aaabbb', $cell2->getSimpleValue());
        $this->assertEquals('string', $cell1->getValueType());
        $this->assertEquals('string', $cell2->getValueType());
        $this->assertEquals('string', $cell3->getValueType());
        $this->assertInstanceOf(Sheet::class, $cell1->getSheet());
        $this->assertEquals(1, $cell1->getRow());
        $this->assertEquals(1, $cell1->getCol());
        $this->assertEquals('A', $cell1->getColRef());
        $this->assertEquals('A1', $cell1->getRef());
        $this->assertEquals(2, $cell2->getRow());
        $this->assertEquals(3, $cell2->getCol());
        $this->assertEquals('C', $cell2->getColRef());
        $this->assertEquals('C2', $cell2->getRef());
        $this->assertFalse($cell2->isMerged());
        $this->assertFalse($cell2->isMerging());
        $this->assertFalse($cell2->hasStyle());
        $this->assertEquals(10.0, $cell1->getColWidth());
        $this->assertEquals(20.0, $cell1->getRowHeight());
        $this->assertEquals(null, $cell2->getColWidth());
        $this->assertEquals(null, $cell2->getRowHeight());

        // Try to get numeric value of non-numeric cell.
        try {
            $cell1->getNumericValue();
        } catch (CellValueWrotypeException $exc) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test if defining merged Cells in Sheet works.
     *
     * @return void
     */
    public function testIfMergingCellsWorks() : void
    {

        // Create Xlsx.
        $xlsx  = new Xlsx();
        $book  = $xlsx->getBook();
        $sheet = $book->addSheet('Tables Test');
        $cell  = $sheet->getCell(1, 1);

        // Set merge.
        $cell->setMerge(2, 2);

        // Reset merge.
        $cell->setMerge(4, 4);

        // Test.
        $this->assertTrue($sheet->getCell(2, 2)->isMerged());
        $this->assertFalse($sheet->getCell(2, 2)->isMerging());
        $this->assertTrue($sheet->getCell(1, 1)->isMerging());
        $this->assertTrue($sheet->getCell(3, 3)->isMerged());
        $this->assertTrue($sheet->getCell(4, 4)->isMerged());

        // Try to add value to merged cell.
        try {
            $sheet->getCell(2, 2)->setValue('aaa');
        } catch (SetValueToMergedCellConflictException $exc) {
            $this->assertTrue(true);
        }

        // Try to add value to merged cell.
        try {
            $sheet->getCell(2, 2)->setValueParts([ [ 'aaa' ], [ 'bbb' ] ]);
        } catch (SetValueToMergedCellConflictException $exc) {
            $this->assertTrue(true);
        }

        // Try to create cell merge conflict.
        try {
            $sheet->getCell(2, 2)->setMerge(2, 2);
        } catch (CellMergeConflictException $exc) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test if wrong color throws (1).
     *
     * @return void
     */
    public function testIfWrongColorThrows1() : void
    {

        $this->expectException(ColorNameOtosetException::class);

        // Create color.
        Color::factory('what?');
    }

    /**
     * Test if wrong color throws (2).
     *
     * @return void
     */
    public function testIfWrongColorThrows2() : void
    {

        $this->expectException(ColorFactoryFopException::class);

        // Create color.
        Color::factory(15, 15);
    }

    /**
     * Test if wrong color throws (3).
     *
     * @return void
     */
    public function testIfWrongColorThrows3() : void
    {

        $this->expectException(ParamWrosynException::class);

        // Create color.
        ( new Color() )->set('GGGGGG');
    }

    /**
     * Test if wrong color throws (red).
     *
     * @return void
     */
    public function testIfWrongColorThrowsRed() : void
    {

        $this->expectException(ParamOtoranException::class);

        // Create color.
        ( new Color() )->setRgb(1000, 255, 255);
    }

    /**
     * Test if wrong color throws (green).
     *
     * @return void
     */
    public function testIfWrongColorThrowsGreen() : void
    {

        $this->expectException(ParamOtoranException::class);

        // Create color.
        ( new Color() )->setRgb(255, 1000, 255);
    }

    /**
     * Test if wrong color throws (blue).
     *
     * @return void
     */
    public function testIfWrongColorThrowsBlue() : void
    {

        $this->expectException(ParamOtoranException::class);

        // Create color.
        ( new Color() )->setRgb(255, 255, 1000);
    }

    /**
     * Test if changing locked style throws.
     *
     * @return void
     */
    public function testIfChangingLockedStyleThrows() : void
    {

        // Create xlsx.
        $xlsx = new Xlsx();

        // Create style.
        $style = new Style($xlsx);
        $style->setFill(Color::factory(68, 114, 196));
        $style->setFont(Font::factory('Courier New', '15 black bold italic underline'));
        $style->setLock(true);

        // Test.
        $this->expectException(StyleLockedException::class);

        // Change locked style.
        $style->setFill(Color::factory(0, 0, 0));
    }

    /**
     * Test if setting aligns works.
     *
     * @return void
     */
    public function testIfSettingAlignWorks() : void
    {

        // Create xlsx.
        $xlsx = new Xlsx();

        // Create style.
        $style = new Style($xlsx);

        // Define proper aligns.
        $this->assertEquals([ 'h' => null, 'v' => 'center' ], $style->setValign('center')->getAlign());
        $this->assertEquals([ 'h' => null, 'v' => 'center' ], $style->setValign('C')->getAlign());
        $this->assertEquals([ 'h' => null, 'v' => 'center' ], $style->setValign('c')->getAlign());
        $this->assertEquals([ 'h' => null, 'v' => 'center' ], $style->setValign('middle')->getAlign());
        $this->assertEquals([ 'h' => null, 'v' => 'center' ], $style->setValign('M')->getAlign());
        $this->assertEquals([ 'h' => null, 'v' => 'center' ], $style->setValign('m')->getAlign());
        $this->assertEquals([ 'h' => null, 'v' => 'top' ], $style->setValign('top')->getAlign());
        $this->assertEquals([ 'h' => null, 'v' => 'top' ], $style->setValign('T')->getAlign());
        $this->assertEquals([ 'h' => null, 'v' => 'top' ], $style->setValign('t')->getAlign());
        $this->assertEquals([ 'h' => null, 'v' => 'bottom' ], $style->setValign('bottom')->getAlign());
        $this->assertEquals([ 'h' => null, 'v' => 'bottom' ], $style->setValign('B')->getAlign());
        $this->assertEquals([ 'h' => null, 'v' => 'bottom' ], $style->setValign('b')->getAlign());
        $this->assertEquals([ 'h' => null, 'v' => null ], $style->setValign(null)->getAlign());

        // Define proper aligns.
        $this->assertEquals([ 'h' => 'center', 'v' => null ], $style->setHalign('center')->getAlign());
        $this->assertEquals([ 'h' => 'center', 'v' => null ], $style->setHalign('C')->getAlign());
        $this->assertEquals([ 'h' => 'center', 'v' => null ], $style->setHalign('c')->getAlign());
        $this->assertEquals([ 'h' => 'center', 'v' => null ], $style->setHalign('middle')->getAlign());
        $this->assertEquals([ 'h' => 'center', 'v' => null ], $style->setHalign('M')->getAlign());
        $this->assertEquals([ 'h' => 'center', 'v' => null ], $style->setHalign('m')->getAlign());
        $this->assertEquals([ 'h' => 'left', 'v' => null ], $style->setHalign('left')->getAlign());
        $this->assertEquals([ 'h' => 'left', 'v' => null ], $style->setHalign('L')->getAlign());
        $this->assertEquals([ 'h' => 'left', 'v' => null ], $style->setHalign('l')->getAlign());
        $this->assertEquals([ 'h' => 'right', 'v' => null ], $style->setHalign('right')->getAlign());
        $this->assertEquals([ 'h' => 'right', 'v' => null ], $style->setHalign('R')->getAlign());
        $this->assertEquals([ 'h' => 'right', 'v' => null ], $style->setHalign('r')->getAlign());
        $this->assertEquals([ 'h' => null, 'v' => null ], $style->setHalign(null)->getAlign());

        // Define inproper aligns.
        try {
            $style->setValign('wrong');
        } catch (VerticalAlignOtosetException $exc) {
            $this->assertTrue(true);
        }

        // Define inproper aligns.
        try {
            $style->setHalign('wrong');
        } catch (HorizontalAlignOtosetException $exc) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test if font factory works.
     *
     * @return void
     */
    public function testIfFontFactoryWorks() : void
    {

        // Create font - first way.
        $font1 = Font::factory('Arial', '15 orange bold italic underline');

        // Test.
        $this->assertEquals('Arial', $font1->getName());
        $this->assertEquals(15, $font1->getSize());
        $this->assertEquals(Color::factory('orange')->get(), $font1->getColor()->get());
        $this->assertTrue($font1->isBold());
        $this->assertTrue($font1->isItalic());
        $this->assertTrue($font1->isUnderline());

        // Create font - second way.
        $font2 = Font::factory($font1);

        // Test.
        $this->assertEquals($font1, $font2);

        // Create font - thrid way.
        $font3 = Font::factory('Times');

        // Test.
        $this->assertEquals('Times', $font3->getName());
        $this->assertFalse($font3->hasSize());
        $this->assertFalse($font3->hasColor());
        $this->assertFalse($font3->hasBold());
        $this->assertFalse($font3->hasItalic());
        $this->assertFalse($font3->hasUnderline());

        // Fourth way - wrong way.
        try {
            Font::factory('Times', '13', 'bold');
        } catch (FontFactoryFopException $exc) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test if fill factory works.
     *
     * @return void
     */
    public function testIfFillFactoryWorks() : void
    {

        // Create font - first way.
        $fill1 = Fill::factory('orange');

        // Test.
        $this->assertEquals(Color::factory('orange')->get(), $fill1->getColor()->get());

        // Create fill - second way.
        $fill2 = Fill::factory($fill1);

        // Test.
        $this->assertEquals($fill1, $fill2);

        // Third way - wrong way.
        try {
            Fill::factory('green', 'light');
        } catch (FillFactoryFopException $exc) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test if trying to get spare ID will throw on too many loops.
     *
     * @return void
     */
    public function testIfFindingSpareIdThrowsAfterOtoranLoop() : void
    {

        // Create xlsx.
        $xlsx  = new Xlsx();
        $book  = $xlsx->getBook();
        $sheet = $book->addSheet('Tables Test');

        // Create collection.
        $collection = [];

        // Define anonymous class to be used for collection.
        $class = new class() {

            /**
             * Sets id for object.
             *
             * @param integer $id Id for object.
             *
             * @return void
             */
            public function setId(int $id) : void
            {

                $this->id = $id;
            }

            /**
             * Gets id of object.
             *
             * @return integer.
             */
            public function getId() : int
            {

                return $this->id;
            }
        };

        // Create collection with 20 items.
        for ($i = 1; $i <= 20; ++$i) {

            // Create item.
            $item = new $class();
            $item->setId($i);

            // Add it to collection.
            $collection[] = $item;
        }

        // Preapre.
        $this->expectException(LookingForSpareIdLoopOtoranException::class);

        // Call.
        $sheet->findSpareId($collection, 1, 10);
    }
}
