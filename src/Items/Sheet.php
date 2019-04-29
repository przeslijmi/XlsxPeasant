<?php declare(strict_types=1);

namespace Przeslijmi\XlsxGenerator\Items;

use Przeslijmi\XlsxGenerator\Items;
use Przeslijmi\XlsxGenerator\Items\Cell;
use Przeslijmi\XlsxGenerator\Xlsx;
use Przeslijmi\XlsxGenerator\Xmls\XlWorksheet;

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
     * Name of Sheet.
     *
     * @var string
     */
    private $name;

    /**
     * XlWorksheet XML object.
     *
     * @var XlWorksheet
     */
    private $xml;

    /**
     * Collection of Cells.
     *
     * @var Cell[]
     */
    private $cells = [];

    /**
     * Constructor.
     *
     * @param Xlsx    $xlsx Parent XLSX object.
     * @param integer $id   Id of Sheet.
     * @param string  $name Name of Sheet.
     *
     * @since v1.0
     */
    public function __construct(Xlsx $xlsx, int $id, string $name)
    {

        // Set.
        $this->id   = $id;
        $this->name = $name;
        $this->xml  = new XlWorksheet($this);

        parent::__construct($xlsx);
    }

    /**
     * Setter for name.
     *
     * @param string $name Name of Sheet.
     *
     * @since  v1.0
     * @return self
     */
    public function setName(string $name) : self
    {

        $this->name = $name;

        return $this;
    }

    /**
     * Getter for id.
     *
     * @since  v1.0
     * @return integer
     */
    public function getId() : int
    {

        return $this->id;
    }

    /**
     * Getter for name.
     *
     * @since  v1.0
     * @return sttring
     */
    public function getName() : string
    {

        return $this->name;
    }

    /**
     * Getter for XlWorksheet XML.
     *
     * @since  v1.0
     * @return XlWorksheet
     */
    public function getXml() : XlWorksheet
    {

        return $this->xml;
    }

    /**
     * Getter for cells.
     *
     * @since  v1.0
     * @return array
     */
    public function getCells() : array
    {

        return $this->cells;
    }

    /**
     * Getter for one Cell. If it does not exists - create one instantly.
     *
     * @param integer $row Cell from which row.
     * @param integer $col Cell from which column.
     *
     * @since  v1.0
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

            // Add to index.
            $this->cells[$row][$col] = $cell;

        }//end if

        // Return from index.
        return $this->cells[$row][$col];
    }

    /**
     * Create cells but only to lock merged cells. If Cell already exists - will cause throws.
     *
     * @param integer $row Cell from which row.
     * @param integer $col Cell from which column.
     *
     * @since  v1.0
     * @return Cell
     */
    public function getCellForMerge(int $row, int $col) : Cell
    {

        // If it already exists - throw.
        if (isset($this->cells[$row][$col]) === true) {
            die('cell conflict');
        }

        // Add to index.
        $this->cells[$row][$col] = new Cell($this, $row, $col, true);

        // Return from index.
        return $this->cells[$row][$col];
    }

    /**
     * Delete created cell.
     *
     * @param integer $row Cell from which row.
     * @param integer $col Cell from which column.
     *
     * @todo   Add check if exists.
     * @since  v1.0
     * @return self
     */
    public function deleteCell(int $row, int $col) : self
    {

        unset($this->cells[$row][$col]);

        return $this;
    }

    /**
     * Getter for ref of first cell in Sheet (most often it will be A1).
     *
     * @since  v1.0
     * @return string
     */
    public function getFirstCellRef() : string
    {

        ksort($this->cells);
        $firstRowId = array_keys($this->cells)[0];

        ksort($this->cells[$firstRowId]);
        $firstColId = array_keys($this->cells[$firstRowId])[0];

        $firstCell = $this->cells[$firstRowId][$firstColId];

        return $firstCell->getRef();
    }

    /**
     * Getter for ref of lass cell in Sheet (max col from max row).
     *
     * @todo   It return poorly - have to check max col in every row not in max row only.
     * @since  v1.0
     * @return string
     */
    public function getLastCellRef() : string
    {

        ksort($this->cells);
        $lastRowId = array_reverse(array_keys($this->cells))[0];

        ksort($this->cells[$lastRowId]);
        $lastColId = array_reverse(array_keys($this->cells[$lastRowId]))[0];

        $lastCell = $this->cells[$lastRowId][$lastColId];

        return $lastCell->getRef();
    }

    /**
     * Getter for dimension ref for this cell, ie. 'A1:T932'.
     *
     * @since  v1.0
     * @return string
     */
    public function getDimensionRef() : string
    {

        return $this->getFirstCellRef() . ':' . $this->getLastCellRef();
    }
}
