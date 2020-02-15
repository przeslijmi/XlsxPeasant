<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant;

use PHPUnit\Framework\TestCase;
use Przeslijmi\XlsxPeasant\Exceptions\TargetDirectoryDonoexException;
use Przeslijmi\XlsxPeasant\Exceptions\TargetFileAlrexException;
use Przeslijmi\XlsxPeasant\Exceptions\TargetFileDeletionFailedException;
use Przeslijmi\XlsxPeasant\Exceptions\TargetFileWrosynException;
use Przeslijmi\XlsxPeasant\Helpers\Char;
use Przeslijmi\XlsxPeasant\Items\Color;
use Przeslijmi\XlsxPeasant\Items\ConditionalFormat\DataBar;
use Przeslijmi\XlsxPeasant\Items\Fill;
use Przeslijmi\XlsxPeasant\Items\Font;
use Przeslijmi\XlsxPeasant\Items\Format\DateFormat;
use Przeslijmi\XlsxPeasant\Items\Format\HiddenFormat;
use Przeslijmi\XlsxPeasant\Items\Format\NumFormat;
use Przeslijmi\XlsxPeasant\Items\Style;
use Przeslijmi\XlsxPeasant\Xlsx;

/**
 * Methods for testing proper creation of Xlsx class.
 */
final class ProperCreationTest extends TestCase
{

    /**
     * Test if generating into nonexisting folder will throw.
     *
     * @return void
     */
    public function testIfCreatingToNonexistingDirThrows() : void
    {

        // Prepare.
        $this->expectException(TargetDirectoryDonoexException::class);

        // Non-existing dir.
        $dir = PRZESLIJMI_XLSXPEASANT_TESTS_OUTPUT_DIRECTORY . 'nonExisting/nonExisting/';

        // Try to generate.
        $xlsx  = new Xlsx();
        $sheet = $xlsx->getBook()->addSheet('Simplest Test');
        $sheet->getCell(1, 1)->setValue('Hello World!');
        $xlsx->generate($dir, true);
    }

    /**
     * Test if generating onto used file will throw.
     *
     * @return void
     */
    public function testIfCreatingOntoUsedFileThrows() : void
    {

        // Lvd.
        $uri = PRZESLIJMI_XLSXPEASANT_TESTS_OUTPUT_DIRECTORY . 'tempFile.xlsx';

        // Generate simplest file.
        $xlsx  = new Xlsx();
        $sheet = $xlsx->getBook()->addSheet('Simplest Test');
        $sheet->getCell(1, 1)->setValue('Hello World!');
        $xlsx->generate($uri, true);

        // Open this file to lock it.
        $fh = fopen($uri, 'r+');
        flock($fh, LOCK_EX|LOCK_NB);

        // Try to generate again to the same file.
        try {
            $xlsx  = new Xlsx();
            $sheet = $xlsx->getBook()->addSheet('Simplest Test');
            $sheet->getCell(1, 1)->setValue('Hello World!');
            $xlsx->generate($uri, true);
        } catch (TargetFileDeletionFailedException $exc) {
            $this->assertTrue(true);
        } finally {

            // Delete file.
            flock($fh, LOCK_UN);
            unlink($uri);
        }
    }

    /**
     * Test if Simplest XLSx can be generated.
     *
     * @return void
     */
    public function testSimplest() : void
    {

        // Lvd.
        $dir = PRZESLIJMI_XLSXPEASANT_TESTS_OUTPUT_DIRECTORY;
        $uri = PRZESLIJMI_XLSXPEASANT_TESTS_OUTPUT_DIRECTORY . 'XlsxPeasant_01_simplestTest.xlsx';

        // Create Xlsx.
        $xlsx  = new Xlsx();
        $sheet = $xlsx->getBook()->addSheet('Simplest Test');
        $sheet->getCell(1, 1)->setValue('Hello World!');

        // Generate.
        $xlsx->generate($uri, true);

        // Test.
        $this->assertTrue(file_exists($uri));

        // Create Xlsx again and check if throws on non-overwrite command.
        try {
            $xlsx  = new Xlsx();
            $sheet = $xlsx->getBook()->addSheet('Simplest Test');
            $sheet->getCell(1, 1)->setValue('Hello World!');
            $xlsx->generate($uri, false);
        } catch (TargetFileAlrexException $sexc) {
            $this->assertTrue(true);
        }

        // Create Xlsx again and check if throws on wrong directory.
        try {
            $xlsx  = new Xlsx();
            $sheet = $xlsx->getBook()->addSheet('Simplest Test');
            $sheet->getCell(1, 1)->setValue('Hello World!');
            $xlsx->generate($dir, true);
        } catch (TargetFileWrosynException $sexc) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test if XLSx with colors can be generated.
     *
     * @return void
     */
    public function testColors() : void
    {

        // Lvd.
        $uri        = PRZESLIJMI_XLSXPEASANT_TESTS_OUTPUT_DIRECTORY . 'XlsxPeasant_02_colorsTest.xlsx';
        $colorsKeys = array_keys(Color::DICTIONARY);
        $colorId    = -1;

        // Create Xlsx.
        $xlsx  = new Xlsx();
        $sheet = $xlsx->getBook()->addSheet('Colors Test');

        // Add filled Cells.
        for ($r = 1; $r <= 20; ++$r) {
            for ($c = 1; $c <= 7; ++$c) {

                ++$colorId;
                $colorName = $colorsKeys[$colorId];

                $xlsx->useFill(new Fill(Color::factory($colorName)));
                $sheet->getCell($r, $c)->setValue($colorName);
            }
        }

        // Restore defaults.
        $xlsx->useFill(null);
        $colorId = -1;

        // Add text color Cells.
        for ($r = 1; $r <= 20; ++$r) {
            for ($c = 9; $c <= 15; ++$c) {

                ++$colorId;
                $colorName = $colorsKeys[$colorId];

                $xlsx->useFont(Font::factory(null, $colorName));
                $sheet->getCell($r, $c)->setValue($colorName);
            }
        }

        // Generate.
        $xlsx->generate($uri, true);

        // Test.
        $this->assertTrue(file_exists($uri));
    }

    /**
     * Test if XLSx with fonts can be generated.
     *
     * @return void
     */
    public function testFonts() : void
    {

        // Lvd.
        $uri    = PRZESLIJMI_XLSXPEASANT_TESTS_OUTPUT_DIRECTORY . 'XlsxPeasant_03_fontsTest.xlsx';
        $fonts  = [
            '',
            '',
            'Arial',
            'Arial Black',
            'Arial Narrow',
            'Calibri',
            'Calibri Light',
            'Courier New',
            'Times New Roman',
            'Trebuchet',
        ];
        $string = 'Brown fox jumps over the lazy dog.';

        // Create Xlsx.
        $xlsx  = new Xlsx();
        $sheet = $xlsx->getBook()->addSheet('Fonts Test');

        // Add fonts names.
        for ($c = 2; $c <= 9; ++$c) {
            $sheet->getCell(1, $c)->setValue($fonts[$c]);
        }

        // Add fonts sizes.
        for ($r = 2; $r <= 20; ++$r) {
            $sheet->getCell($r, 1)->setValue('size: ' . $r);
        }

        // Add font Cells.
        for ($r = 2; $r <= 20; ++$r) {
            for ($c = 2; $c <= 9; ++$c) {
                $xlsx->useFont(Font::factory($fonts[$c], (string) $r));
                $sheet->getCell($r, $c)->setValue($string);
            }
        }

        // Generate.
        $xlsx->generate($uri, true);

        // Test.
        $this->assertTrue(file_exists($uri));
    }

    /**
     * Test if XLSx with fonts can be generated.
     *
     * @return void
     */
    public function testUtf16() : void
    {

        // Lvd.
        $uri = PRZESLIJMI_XLSXPEASANT_TESTS_OUTPUT_DIRECTORY . 'XlsxPeasant_04_utf16Test.xlsx';

        // Create Xlsx.
        $xlsx  = new Xlsx();
        $sheet = $xlsx->getBook()->addSheet('UTF-16 Chars Test');

        // Define what to add.
        for ($i = 0; $i <= hexdec('3FF'); ++$i) {

            // Lvd.
            $oneRow = 10;
            $rTitle = (int) ( ( ( floor(( $i + $oneRow ) / $oneRow) - 1 ) * 2 ) + 1 );
            $rChar  = (int) ( $rTitle + 1 );
            $c      = (int) ( ( $i + $oneRow ) - ( floor(( $i + $oneRow ) / $oneRow) ) * $oneRow + 1 );

            // Find character.
            if ($i <= 32 || ( $i >= 128 && $i <= 159 )) {
                $char = 'CTRL';
            } else {
                $char = Char::byDec($i);
            }

            // Save Titles.
            $addTitles[] = [
                'row'   => $rTitle,
                'col'   => $c,
                'title' => strtoupper('U+' . str_pad(dechex($i), 4, '0', STR_PAD_LEFT)),
            ];

            // Save Chars.
            $addChars[] = [
                'row'  => $rChar,
                'col'  => $c,
                'char' => $char,
            ];
        }//end for

        // Define style for titles.
        $xlsx->useAlign('CM');
        $xlsx->useFill(new Fill(Color::factory('CCCCCC')));
        $xlsx->useFont(Font::factory(null, 'bold'));

        // Add Titles.
        foreach ($addTitles as $add) {
            $sheet->getCell($add['row'], $add['col'])->setValue($add['title']);
        }

        // Define style for characters.
        $xlsx->useFill(new Fill(Color::factory('EEEEEE')));
        $xlsx->useFont(null);

        // Add Chars.
        foreach ($addChars as $add) {
            $sheet->getCell($add['row'], $add['col'])->setValue($add['char']);
        }

        // Generate.
        $xlsx->generate($uri, true);

        // Test.
        $this->assertTrue(file_exists($uri));
    }

    /**
     * Test if XLSx with Styles can be generated.
     *
     * @return void
     */
    public function testStyles() : void
    {

        // Lvd.
        $uri = PRZESLIJMI_XLSXPEASANT_TESTS_OUTPUT_DIRECTORY . 'XlsxPeasant_05_styles.xlsx';

        // Create Xlsx.
        $xlsx  = new Xlsx();
        $sheet = $xlsx->getBook()->addSheet('Styles Test');

        // Set defaults.
        $xlsx->useFill(new Fill(Color::factory('DDDDDD')));
        $sheet->getCell(1, 1)->setValue('Change default settings');
        $xlsx->useFill(null);
        $xlsx->setDefault('fontColor', Color::factory('red'));
        $xlsx->setDefault('fontSize', 19);
        $xlsx->setDefault('fontName', 'Times New Roman');
        $sheet->getCell(2, 1)->setValue('This is text with new default settings (red, 19, Times New Roman).');

        // Show aligns.
        $xlsx->useFill(new Fill(Color::factory('DDDDDD')));
        $sheet->getCell(4, 1)->setValue('Align');
        $xlsx->useFill(null);
        $xlsx->useAlign('LT');
        $sheet->getCell(5, 1)->setValue('LT');
        $xlsx->useAlign('CT');
        $sheet->getCell(5, 2)->setValue('CT');
        $xlsx->useAlign('RT');
        $sheet->getCell(5, 3)->setValue('RT');
        $xlsx->useAlign('LM');
        $sheet->getCell(6, 1)->setValue('LM');
        $xlsx->useAlign('CM');
        $sheet->getCell(6, 2)->setValue('CM');
        $xlsx->useAlign('RM');
        $sheet->getCell(6, 3)->setValue('RM');
        $xlsx->useAlign('LB');
        $sheet->getCell(7, 1)->setValue('LB');
        $xlsx->useAlign('CB');
        $sheet->getCell(7, 2)->setValue('CB');
        $xlsx->useAlign('RB');
        $sheet->getCell(7, 3)->setValue('RB');
        $xlsx->useAlign(null);

        // Show variants.
        $xlsx->useFill(new Fill(Color::factory('DDDDDD')));
        $sheet->getCell(9, 1)->setValue('Font variant');
        $xlsx->useFill(null);
        $xlsx->useFont(Font::factory(null, 'bold'));
        $sheet->getCell(10, 1)->setValue('bold');
        $xlsx->useFont(Font::factory(null, 'italic'));
        $sheet->getCell(10, 2)->setValue('italic');
        $xlsx->useFont(Font::factory(null, 'underline'));
        $sheet->getCell(10, 3)->setValue('underline');
        $xlsx->useFont(Font::factory(null, 'bold italic'));
        $sheet->getCell(10, 4)->setValue('bold italic');
        $xlsx->useFont(Font::factory(null, 'bold underline'));
        $sheet->getCell(10, 5)->setValue('bold underline');
        $xlsx->useFont(Font::factory(null, 'italic underline'));
        $sheet->getCell(10, 6)->setValue('italic underline');
        $xlsx->useFont(Font::factory(null, 'bold italic underline'));
        $sheet->getCell(10, 7)->setValue('bold italic underline');
        $xlsx->useFont(null);

        // Show text wrap.
        $xlsx->useFill(new Fill(Color::factory('DDDDDD')));
        $sheet->getCell(12, 1)->setValue('Text wrapping');
        $xlsx->useFill(null);
        $xlsx->useWrapText(false);
        $sheet->getCell(13, 1)->setValue('text without wrap - will flow to next cells');
        $xlsx->useWrapText(true);
        $sheet->getCell(14, 1)->setValue('text with wrap - will flow vertically enlarging cell');
        $xlsx->useWrapText(null);

        // Show diff fonts in one Cell.
        $xlsx->useFill(new Fill(Color::factory('DDDDDD')));
        $sheet->getCell(16, 1)->setValue('Two Fonts in one Cell');
        $xlsx->useFill(null);
        $sheet->getCell(17, 1)->setValueParts(
            [
                [ 'Normal ' ],
                [ 'changed ', Font::factory(null, 'black 20 bold') ],
                [ 'and normal' ]
            ]
        );
        $sheet->getCell(18, 1)->setValueParts(
            [
                [ 'Normal ' ],
                [ 'changed ', Font::factory(null, 'black 20 bold') ],
                [ 'and normal' ]
            ]
        );

        // Show use of defined Styles.
        $xlsx->useFill(new Fill(Color::factory('DDDDDD')));
        $sheet->getCell(20, 1)->setValue('Predefined Styles');
        $xlsx->useFill(null);
        $xlsx->useStyle(
            ( new Style($xlsx) )
                ->setFill(Color::factory(68, 114, 196))
                ->setFont(Font::factory('Courier New', '15 black bold italic underline'))
        );
        $sheet->getCell(21, 1)->setValue('Courier New, 15, black, bold, italic, underline; Fill 68:114:196.');
        $xlsx->useStyle(
            ( new Style($xlsx) )
                ->setFontName('Courier New')
        );
        $sheet->getCell(22, 1)->setValue('Courier New');
        $xlsx->useStyle(
            ( new Style($xlsx) )
                ->setFontSize(19)
        );
        $sheet->getCell(22, 2)->setValue('19');
        $xlsx->useStyle(
            ( new Style($xlsx) )
                ->setFontColor(Color::factory('green'))
        );
        $sheet->getCell(22, 3)->setValue('green');

        // Show use of Format.
        $xlsx->useDefaults();
        $xlsx->useFill(new Fill(Color::factory('DDDDDD')));
        $sheet->getCell(24, 1)->setValue('Formats');
        $xlsx->useFill(null);
        $sheet->getCell(25, 1)->setValue('date:');
        $sheet->getCell(26, 1)->setValue('unit:');
        $sheet->getCell(27, 1)->setValue('currency:');
        $sheet->getCell(28, 1)->setValue('percentage:');
        $sheet->getCell(29, 1)->setValue('hidden:');
        $xlsx->useFormat(new DateFormat());
        $sheet->getCell(25, 2)->setValue(43466);
        $xlsx->useFormat(new NumFormat(0, 0, 'szt.'));
        $sheet->getCell(26, 2)->setValue(10);
        $xlsx->useFormat(new NumFormat(2, 0, 'zł'));
        $sheet->getCell(27, 2)->setValue(19.99);
        $xlsx->useFormat(new NumFormat(2, 0, '%'));
        $sheet->getCell(28, 2)->setValue(34.78);
        $xlsx->useFormat(new HiddenFormat());
        $sheet->getCell(29, 2)->setValue('hidden');
        $xlsx->useFormat(null);

        // Show use of multistyled cells.
        $xlsx->useDefaults();
        $xlsx->useFill(new Fill(Color::factory('DDDDDD')));
        $sheet->getCell(31, 1)->setValue('Multipart cells');
        $xlsx->useFill(null);
        $sheet->getCell(32, 1)->setValueParts([
            [ 'default ' ],
            [ 'and now bold in one cell', Font::factory(null, 'bold') ]
        ]);
        $xlsx->useFont(Font::factory(null, '10'));
        $sheet->getCell(33, 1)->setValueParts([
            [ 'small ' ],
            [ 'and now totaly different in one cell', Font::factory('Arial', 'orange 20 bold italic underline') ]
        ]);
        $xlsx->useFont(null);
        $xlsx->useFont(Font::factory(null, 'italic'));
        $sheet->getCell(34, 1)->setValueParts([
            [ 'italic ' ],
            [ 'and now also bold in one cell', Font::factory(null, 'bold') ]
        ]);
        $sheet->getCell(35, 1)->setValueParts([
            [ 'two parts ' ],
            [ 'no font diff' ]
        ]);
        $xlsx->useFont(null);

        // Show use of standard settings.
        $xlsx->useDefaults();
        $sheet->setRowHeight(37, 34.00);
        $sheet->setColWidth(1, 22.00);
        $sheet->getCell(37, 1)->setValue('This Cell is as defaults show but row has more height.');

        // Generate.
        $xlsx->generate($uri, true);

        // Test.
        $this->assertTrue(file_exists($uri));
    }

    /**
     * Test if XLSx with may merge Cells in Sheet.
     *
     * @return void
     */
    public function testMergingCells() : void
    {

        // Lvd.
        $uri = PRZESLIJMI_XLSXPEASANT_TESTS_OUTPUT_DIRECTORY . 'XlsxPeasant_06_mergingCells.xlsx';

        // Create Xlsx.
        $xlsx  = new Xlsx();
        $sheet = $xlsx->getBook()->addSheet('Merging Cells Test');

        // Set defaults.
        $xlsx->useFill(new Fill(Color::factory('green')));
        $sheet->getCell(1, 1)->setValue('This Cell merges two extra columns')->setMerge(1, 4);
        $xlsx->useFill(new Fill(Color::factory('red')));
        $sheet->getCell(2, 1)->setValue('This Cell merges two extra rows.')->setMerge(3, 1);
        $xlsx->useFill(new Fill(Color::factory('blue')));
        $sheet->getCell(2, 2)->setValue('This Cell merges two extra rows and two extra colums.')->setMerge(3, 3);

        // Generate.
        $xlsx->generate($uri, true);

        // Test.
        $this->assertTrue(file_exists($uri));
    }

    /**
     * Test if XLSx with many Sheets can be generated.
     *
     * @return void
     *
     * @phpcs:disable Zend.NamingConventions.ValidVariableName.ContainsNumbers
     */
    public function testMultiSheets() : void
    {

        // Lvd.
        $uri = PRZESLIJMI_XLSXPEASANT_TESTS_OUTPUT_DIRECTORY . 'XlsxPeasant_07_multiSheets.xlsx';

        // Create Xlsx.
        $xlsx   = new Xlsx();
        $sheet1 = $xlsx->getBook()->addSheet('Sheet1 Test');
        $sheet2 = $xlsx->getBook()->addSheet('Sheet2 Test');
        $sheet3 = $xlsx->getBook()->addSheet('Sheet3 Test');

        // Set defaults.
        $sheet1->getCell(1, 1)->setValue('This is contents of Sheet1.');
        $sheet2->getCell(1, 1)->setValue('This is contents of Sheet2.');
        $sheet3->getCell(1, 1)->setValue('This is contents of Sheet3.');

        // Generate.
        $xlsx->generate($uri, true);

        // Test.
        $this->assertTrue(file_exists($uri));
    }

    /**
     * Test if XLSx with Tables can be generated.
     *
     * @return void
     */
    public function testTables() : void
    {

        // Lvd.
        $uri = PRZESLIJMI_XLSXPEASANT_TESTS_OUTPUT_DIRECTORY . 'XlsxPeasant_08_tables.xlsx';

        // Create Xlsx.
        $xlsx  = new Xlsx();
        $sheet = $xlsx->getBook()->addSheet('Tables Test');

        // Prepare data 1.
        $data1 = [
            [
                'first name' => 'Jacob',
                'surname'    => 'Rampling',
                'department' => 'Training',
                'age'        => 28,
                'exam'       => 43461,
                'readiness'  => 3,
            ],
            [
                'first name' => 'Edward',
                'surname'    => 'Welch',
                'department' => 'Sales',
                'age'        => 27,
                'exam'       => 43462,
                'readiness'  => 3,
            ],
            [
                'first name' => 'Diane',
                'surname'    => 'Knox',
                'department' => 'Research and Development',
                'age'        => 29,
                'exam'       => 43463,
                'readiness'  => 2,
            ],
            [
                'first name' => 'Claire',
                'surname'    => 'Wallace',
                'department' => 'Research and Development',
                'age'        => 26,
                'exam'       => 43464,
                'readiness'  => 1,
            ],
            [
                'first name' => 'Anne',
                'surname'    => 'Bailey',
                'department' => 'Research and Development',
                'age'        => 27,
                'exam'       => 43465,
                'readiness'  => 3,
            ],
            [
                'first name' => 'Owen',
                'surname'    => 'Lawrence',
                'department' => 'Services',
                'age'        => 28,
                'exam'       => 43466,
                'readiness'  => 4,
            ],
            [
                'first name' => 'Joshua',
                'surname'    => 'Manning',
                'department' => 'Sales',
                'age'        => 32,
                'exam'       => 43467,
                'readiness'  => 2,
            ],
            [
                'first name' => 'Adam',
                'surname'    => 'Blake',
                'department' => 'Services',
                'age'        => 25,
                'exam'       => 43468,
                'readiness'  => 2,
            ],
            [
                'first name' => 'Anne',
                'surname'    => 'White',
                'department' => 'Services',
                'age'        => 26,
                'exam'       => 43469,
                'readiness'  => 1,
            ],
            [
                'first name' => 'Julia',
                'surname'    => 'Paterson',
                'department' => 'Services',
                'age'        => 28,
                'exam'       => 43460,
                'readiness'  => 1,
            ],
        ];

        // Prepare data 2.
        $data2 = [
            [
                'department' => 'Research and Development',
                'phone' => '11122233',
            ],
            [
                'department' => 'Sales',
                'phone' => '11122234',
            ],
            [
                'department' => 'Services',
                'phone' => '11122235',
            ],
            [
                'department' => 'Training',
                'phone' => '11122236',
            ],
        ];

        // Add table 1.
        $table1 = $sheet->addTable('Workers', 1, 1);
        $table1->addColumns([
            'first name',
            'surname',
            'department',
            'age',
            'exam',
            'readiness',
        ]);
        $table1->getColumnByName('exam')->setFormat(new DateFormat());
        $table1->getColumnByName('age')->setFormat(new NumFormat(0, 0, 'ys'));
        $table1->getColumnByName('readiness')->setConditionalFormat(new DataBar());
        $table1->addData($data1);

        // Add table 2.
        $table2 = $sheet->addTable('Dep.Phones', 2, 8);
        $table2->addColumns([ 'department', 'phone' ]);
        $table2->setData($data2);

        // Generate.
        $xlsx->generate($uri, true);

        // Test.
        $this->assertTrue(file_exists($uri));
    }
}
