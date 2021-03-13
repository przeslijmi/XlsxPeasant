<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items;

use Exception;
use Przeslijmi\Sivalidator\RegEx;
use Przeslijmi\XlsxPeasant\Exceptions\CellMergeConflictException;
use Przeslijmi\XlsxPeasant\Exceptions\SheetIdOtoranException;
use Przeslijmi\XlsxPeasant\Exceptions\SheetNameAlrexException;
use Przeslijmi\XlsxPeasant\Exceptions\SheetNameWrosynException;
use Przeslijmi\XlsxPeasant\Exceptions\TableCreaoranException;
use Przeslijmi\XlsxPeasant\Exceptions\TableCreationFopException;
use Przeslijmi\XlsxPeasant\Helpers\Tools as XlsxTools;
use Przeslijmi\XlsxPeasant\Items;
use Przeslijmi\XlsxPeasant\Items\Book;
use Przeslijmi\XlsxPeasant\Items\Cell;
use Przeslijmi\XlsxPeasant\Items\Table;
use Przeslijmi\XlsxPeasant\Xlsx;
use Przeslijmi\XlsxPeasant\Xmls\XlRelsWorksheet;
use Przeslijmi\XlsxPeasant\Xmls\XlWorksheet;

/**
 * Sheet object - child of Book - parent of Cell objects.
 */
class Sheet extends Items
{

    /**
     * Id of Sheet.
     *
     * @var integer
     */
    private $id;

    /**
     * UUID of Sheet.
     *
     * @var string
     */
    private $uuid;

    /**
     * Name of Sheet.
     *
     * @var string
     */
    private $name;

    /**
     * Collection of Cells.
     *
     * @var Cell[]
     */
    private $cells = [];

    /**
     * XlWorksheet XML object.
     *
     * @var XlWorksheet
     */
    private $xml;

    /**
     * Book object.
     *
     * @var Book
     */
    private $book;

    /**
     * Defined widths of columns.
     *
     * @var int[]
     */
    private $colsWidth = [];

    /**
     * Defined heights of rows.
     *
     * @var int[]
     */
    private $rowsHeight = [];

    /**
     * Constructor.
     *
     * @param Xlsx         $xlsx Parent XLSX object.
     * @param string       $name Name of Sheet.
     * @param null|integer $id   Optional Id of Sheet.
     */
    public function __construct(Xlsx $xlsx, string $name, ?int $id = null)
    {

        // Set XLSX.
        parent::__construct($xlsx);

        // Set.
        $this->setId($id);
        $this->setName($name);
        $this->xml     = new XlWorksheet($this);
        $this->xmlRels = new XlRelsWorksheet($this);
        $this->uuid    = XlsxTools::createUuid();
    }

    /**
     * Setter for id.
     *
     * @param integer $id Id of Table.
     *
     * @throws SheetIdOtoranException When ID is below 1.
     * @return self
     */
    private function setId(?int $id = null) : self
    {

        // Find id if not given.
        if ($id === null) {
            $id = $this->findSpareId($this->getXlsx()->getBook()->getSheets(false));
        }

        // Check.
        if ($id < 1) {
            throw new SheetIdOtoranException($id);
        }

        // Set.
        $this->id = $id;

        return $this;
    }

    /**
     * Getter for id.
     *
     * @return integer
     */
    public function getId() : int
    {

        return $this->id;
    }

    /**
     * Getter for uuid.
     *
     * @return string
     */
    public function getUuid() : string
    {

        return $this->uuid;
    }

    /**
     * Setter for name.
     *
     * @param string $name Name of Sheet.
     *
     * @throws SheetNameWrosynException When Sheet name has wrong syntax.
     * @throws SheetNameAlrexException  When Sheet name is already taken (exists).
     * @return self
     */
    public function setName(string $name) : self
    {

        // Test.
        try {

            // Lvd.
            $special = '\\!\\@\\#\\$\\%\\^\\&\\(\\)\\_\\+\\-\\=\\{\\}\\|\\"\\;\\.\\,\\ \\<\\>';

            // Add polish accented letters.
            $special .= 'ążśźęćńółĄŻŚŹĘĆŃÓŁ';

            // Proper chars, at least 2 chars length, max length.
            RegEx::ifMatches($name, '/^([\\\\A-Z0-9' . $special . ']){1,256}$/i');

        } catch (Exception $exc) {
            throw new SheetNameWrosynException($name, $exc);
        }

        // Test duplication of names of Tables in whole Xlsx.
        foreach ($this->getXlsx()->getBook()->getSheets(false) as $sheetInBook) {

            // Throw.
            if ($sheetInBook->getName() === $name) {
                throw new SheetNameAlrexException($name);
            }
        }

        // Save.
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for name.
     *
     * @return sttring
     */
    public function getName() : string
    {

        return $this->name;
    }

    /**
     * Setter for book.
     *
     * @param Book $book Book parent object.
     *
     * @return self
     */
    public function setBook(Book $book) : self
    {

        $this->book = $book;

        return $this;
    }

    /**
     * Getter for book.
     *
     * @return Book
     */
    public function getBook() : Book
    {

        return $this->book;
    }

    /**
     * Getter for XlWorksheet XML.
     *
     * @return XlWorksheet
     */
    public function getXml() : XlWorksheet
    {

        return $this->xml;
    }

    /**
     * Getter for XlWorksheet XML.
     *
     * @return XlRelsWorksheet
     */
    public function getRelsXml() : XlRelsWorksheet
    {

        return $this->xmlRels;
    }

    /**
     * Adds new Table to XLSX.
     *
     * @param string       $name Name of Table.
     * @param integer      $row  Row to start Table in.
     * @param integer      $col  Col to start Table in.
     * @param null|integer $id   Optional id of Table.
     *
     * @throws TableCreationFopException When creation of Table somehow failed.
     * @return Table
     */
    public function addTable(string $name, int $row, int $col, ?int $id = null) : Table
    {

        // Try to add Table to this Sheet.
        try {
            $table = $this->getBook()->addTable($name, $id);
            $table->useAt($this, $row, $col);
        } catch (Exception $exc) {
            throw new TableCreationFopException($name, $exc);
        }

        return $table;
    }

    /**
     * Getter for Cells.
     *
     * @return Cell[]
     */
    public function getCells() : array
    {

        ksort($this->cells);

        foreach ($this->cells as $rowId => $row) {
            if (is_array($row) === true) {
                ksort($this->cells[$rowId]);
            }
        }

        return $this->cells;
    }

    /**
     * Getter for one Cell. If it does not exists - create one instantly.
     *
     * @param integer $row Cell from which row.
     * @param integer $col Cell from which col.
     *
     * @return Cell
     */
    public function getCell(int $row, int $col) : Cell
    {

        // If it is not present - create it.
        if (isset($this->cells[$row][$col]) === false) {

            // Create.
            $cell = new Cell($this, $row, $col);

            // Define standard properties.
            if ($this->xlsx->getStyleToUse() !== null) {
                $cell->setStyle($this->xlsx->getStyleToUse());
            }
            if ($this->xlsx->getFillToUse() !== null) {
                $cell->getStyle()->setFill($this->xlsx->getFillToUse());
            }
            if ($this->xlsx->getFontToUse() !== null) {
                $cell->getStyle()->setFont($this->xlsx->getFontToUse());
            }
            if ($this->xlsx->getAlignToUse() !== null) {
                $cell->getStyle()->setAlign($this->xlsx->getAlignToUse());
            }
            if ($this->xlsx->getWrapTextToUse() !== null) {
                $cell->getStyle()->setWrapText($this->xlsx->getWrapTextToUse());
            }
            if ($this->xlsx->getFormatToUse() !== null) {
                $cell->getStyle()->setFormat($this->xlsx->getFormatToUse());
            }

            // Add to index.
            $this->cells[$row][$col] = $cell;

            return $cell;
        }//end if

        // Return from index.
        return $this->cells[$row][$col];
    }

    public function getRow(int $row) : Row
    {

        // If it already exists - throw.
        // @todo

        // Add to index.
        $rowObj            = new Row($this, $row);
        $this->cells[$row] = $rowObj;

        return $rowObj;
    }
    /**
     * Create cells but only to lock merged cells. If Cell already exists - will cause throws.
     *
     * @param integer $row Cell from which row.
     * @param integer $col Cell from which col.
     *
     * @throws CellMergeConflictException When merge is trying to overwrite existing Cell.
     * @return Cell
     */
    public function getCellForMerge(int $row, int $col) : Cell
    {

        // If it already exists - throw.
        if (isset($this->cells[$row][$col]) === true) {
            $cellRef = XlsxTools::convToCellRef($row, $col);
            throw new CellMergeConflictException($cellRef);
        }

        // Add to index.
        $cell                    = new Cell($this, $row, $col, true);
        $this->cells[$row][$col] = $cell;

        return $cell;
    }

    /**
     * Delete created Cell.
     *
     * @param integer $row Cell from which row.
     * @param integer $col Cell from which col.
     *
     * @return self
     */
    public function deleteCell(int $row, int $col) : self
    {

        if (isset($this->cells[$row][$col]) === true) {
            unset($this->cells[$row][$col]);
        }

        return $this;
    }

    /**
     * Getter for ref of first cell in Sheet (most often it will be A1).
     *
     * @return string
     */
    public function getFirstCellRef() : string
    {

        // Shortcut.
        if (empty($this->cells) === true) {
            return 'A1';
        }

        // Get first row ID.
        ksort($this->cells);
        $firstRowId = array_keys($this->cells)[0];

        // Get first col ID.
        ksort($this->cells[$firstRowId]);
        $firstColId = array_keys($this->cells[$firstRowId])[0];

        // Get first Cell.
        $firstCell = $this->cells[$firstRowId][$firstColId];

        return $firstCell->getRef();
    }

    /**
     * Getter for ref of lass cell in Sheet (max col from all rows).
     *
     * @return string
     */
    public function getLastCellRef() : string
    {

        // Shortcut.
        if (empty($this->cells) === true) {
            return 'A1';
        }

        // Lvd.
        $lastColId = 1;

        // Go thru every row looking for longest row (highest col in that row).
        foreach ($this->cells as $lastRowId => $row) {

            // @temp
            if (is_array($row) === false) {
                continue;
            }

            // Get last col in this row.
            ksort($row);
            $lastColIdHere = array_reverse(array_keys($row))[0];

            // If this is bigger than last winner - use it from now on.
            if ($lastColIdHere > $lastColId) {
                $lastColId = $lastColIdHere;
            }
        }

        return XlsxTools::convToCellRef($lastRowId, $lastColId);
    }

    /**
     * Getter for dimension ref for this cell, ie. 'A1:T932'.
     *
     * @return string
     */
    public function getDimensionRef() : string
    {

        return $this->getFirstCellRef() . ':' . $this->getLastCellRef();
    }

    /**
     * Getter for all Tables in this Sheet.
     *
     * @return Tables[]
     */
    public function getTables() : array
    {

        return $this->getXlsx()->getBook()->getTables($this);
    }

    /**
     * Checks if this Sheet has any Table.
     *
     * @return boolean
     */
    public function hasTables() : bool
    {

        return $this->getXlsx()->getBook()->hasTables($this);
    }

    /**
     * Setter for column width.
     *
     * @param integer    $col   For which column width is to be set.
     * @param null|float $width Desired width or null to make width default.
     *
     * @return self
     */
    public function setColWidth(int $col, ?float $width = null) : self
    {

        // Set defualt col width.
        if ($width === null) {
            unset($this->colsWidth[$col]);

            return $this;
        }

        // Define given col width.
        $this->colsWidth[$col] = $width;

        return $this;
    }

    /**
     * Getter for all defined witdth for all cols.
     *
     * @return float[] Array with width of subsequent rows (key is row id).
     */
    public function getColsWidth() : array
    {

        return $this->colsWidth;
    }

    /**
     * Getter for witdth of given col.
     *
     * @param integer $col For which column width has to be returned.
     *
     * @return null|float Float as col width or null if width for given col has not been defined.
     */
    public function getColWidth(int $col) : ?float
    {

        return ( $this->colsWidth[$col] ?? null );
    }

    /**
     * Setter for row height.
     *
     * @param integer    $row    For which row height is to be set.
     * @param null|float $height Desired height or null to make height default.
     *
     * @return self
     */
    public function setRowHeight(int $row, ?float $height = null) : self
    {

        // Set defualt col width.
        if ($height === null) {
            unset($this->rowsHeight[$row]);

            return $this;
        }

        // Define given col width.
        $this->rowsHeight[$row] = $height;

        return $this;
    }

    /**
     * Getter for row height.
     *
     * @param integer $row For which row height has to be returned.
     *
     * @return null|float Float as row height or null if height for given row has not been defined.
     */
    public function getRowHeight(int $row) : ?float
    {

        return ( $this->rowsHeight[$row] ?? null );
    }
}
