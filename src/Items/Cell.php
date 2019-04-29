<?php declare(strict_types=1);

namespace Przeslijmi\XlsxGenerator\Items;

use Przeslijmi\XlsxGenerator\Items;
use Przeslijmi\XlsxGenerator\Items\Color;
use Przeslijmi\XlsxGenerator\Items\Fill;
use Przeslijmi\XlsxGenerator\Items\Sheet;
use Przeslijmi\XlsxGenerator\Items\Style;
use Przeslijmi\XlsxGenerator\Items\ValuePart;
use Przeslijmi\XlsxGenerator\Tools as XlsxTools;

/**
 * One Cell inside one Sheet of one Xlsx.
 */
class Cell extends Items
{

    /**
     * Parent Sheet object.
     *
     * @var Sheet
     */
    private $sheet;

    /**
     * Row id (starting with 1).
     *
     * @var integer
     */
    private $row;

    /**
     * Column id (starting with 1).
     *
     * @var integer
     */
    private $col;

    /**
     * Collection of values for this Cell.
     *
     * @var ValuePart[]
     */
    private $valueParts;

    /**
     * If this cell is hidden because it is overriden by other merging cell.
     *
     * @var boolean
     */
    private $merged = false;

    /**
     * How many rows this cell merges (default 1 = no rows).
     *
     * @var integer
     */
    private $mergeRows = 1;

    /**
     * How many columns this cell merges (default 1 = no columns).
     *
     * @var integer
     */
    private $mergeCols = 1;

    /**
     * Id of sharedStrings (filled on creation of XLSX).
     *
     * @var integer
     */
    private $sharedStringsId;

    /**
     * Style defined for this cell.
     *
     * @var Style
     */
    private $style;

    /**
     * Construct.
     *
     * @param Sheet   $sheet  Parent Sheet object.
     * @param integer $row    Row id of this cell (starting with 1).
     * @param integer $col    Column id of this cell (starting with 1).
     * @param boolean $merged If this cell is merged (its "almost-cell" then).
     *
     * @since v1.0
     */
    public function __construct(Sheet $sheet, int $row, int $col, bool $merged = false)
    {

        // Lvd.
        $this->sheet  = $sheet;
        $this->row    = $row;
        $this->col    = $col;
        $this->merged = $merged;

        parent::__construct($sheet->getXlsx());
    }

    /**
     * Setter for value of the cell as one part.
     *
     * @param string $valueParts Contents of the cell.
     *
     * @since  v1.0
     * @return self
     */
    public function setValue(string $valueParts) : self
    {

        // @todo
        if ($this->merged === true) {
            die('cant add valueParts to merged cell');
        }

        // Save as new ValuePart.
        $this->valueParts = [ new ValuePart($this, $valueParts) ];

        return $this;
    }

    /**
     * Setter for value of the cell as multiple parts.
     *
     * @param array $parts Contents of the cell as multiple parts.
     *
     * @since  v1.0
     * @return self
     */
    public function setValueParts(array $parts) : self
    {

        // Lvd.
        $this->valueParts = [];

        // Add subsequent parts.
        foreach ($parts as $partInfo) {
            $this->valueParts[] = new ValuePart($this, ...$partInfo);
        }

        return $this;
    }

    /**
     * Getter for all parts of value.
     *
     * @since  v1.0
     * @return array
     */
    public function getValue() : array
    {

        return $this->valueParts;
    }

    /**
     * Getter for contents ID - used by XlWorksheet while generating XLSX.
     *
     * @since  v1.0
     * @return integer
     */
    public function getSharedStringsId() : int
    {

        // If unknown. Register and receive it.
        // After calling registerValue() - setShardStringId() is called.
        if ($this->sharedStringsId === null) {
            $this->getXlsx()->getSharedStrings()->registerValue($this);
        }

        return $this->sharedStringsId;
    }

    /**
     * Setter for contents ID - called by XlWorksheet while generating XLSX.
     *
     * @param integer $id SharedStrings ID.
     *
     * @since  v1.0
     * @return $this
     */
    public function setShardStringId(int $id) : self
    {

        $this->sharedStringsId = $id;

        return $this;
    }

    /**
     * Getter for row id.
     *
     * @since  v1.0
     * @return integer
     */
    public function getRow() : int
    {

        return $this->row;
    }

    /**
     * Getter for col id.
     *
     * @since  v1.0
     * @return integer
     */
    public function getCol() : int
    {

        return $this->col;
    }

    /**
     * Getter for Sheet.
     *
     * @since  v1.0
     * @return Sheet
     */
    public function getSheet() : Sheet
    {

        return $this->sheet;
    }

    /**
     * Getter for col ref (eg. A, B, C).
     *
     * @since  v1.0
     * @return string
     */
    public function getColRef() : string
    {

        return XlsxTools::convNumberToRef($this->col);
    }

    /**
     * Getter for Cell ref (eg. A1, B4, C2).
     *
     * @since  v1.0
     * @return string
     */
    public function getRef() : string
    {

        return $this->getColRef() . $this->getRow();
    }

    /**
     * Is Cell is a subject to be merged (is hidden, overridden by merging cell).
     *
     * @since  v1.0
     * @return boolean
     */
    public function isMerged() : bool
    {

        return $this->merged;
    }

    /**
     * Is Cell is merging other cells (overwriting them with its value).
     *
     * @since  v1.0
     * @return boolean
     */
    public function isMerging() : bool
    {

        return ( $this->mergeCols > 1 || $this->mergeRows > 1 );
    }

    /**
     * Make this Cell merge other cells.
     *
     * @param integer $rows How many rows it have to merge.
     * @param integer $cols How many columns it have to merge.
     *
     * @since  v1.0
     * @return self
     */
    public function setMerge(int $rows, int $cols) : self
    {

        // Delete old merged cells.
        if ($this->isMerging() === true) {

            // Go through every cell of merge-block and add "blid" cell..
            for ($r = $this->row; $r < ( $this->row + $rows ); ++$r) {
                for ($c = $this->col; $c < ( $this->col + $cols ); ++$c) {

                    // Ignore adding this cell. It is already added.
                    if ($r === $this->row && $c === $this->col) {
                        continue;
                    }

                    // Call to create blind cell.
                    $this->sheet->deleteCell($r, $c);
                }
            }
        }

        // Go through every cell of merge-block and add "blid" cell..
        for ($r = $this->row; $r < ( $this->row + $rows ); ++$r) {
            for ($c = $this->col; $c < ( $this->col + $cols ); ++$c) {

                // Ignore adding this cell. It is already added.
                if ($r === $this->row && $c === $this->col) {
                    continue;
                }

                // Call to create blind cell.
                $this->sheet->getCellForMerge($r, $c);
            }
        }

        // Set object properties.
        $this->mergeRows = $rows;
        $this->mergeCols = $cols;

        return $this;
    }

    /**
     * Getter for merge ref (eg. A1:A2, B4:C6, C2:C6).
     *
     * @since  v1.0
     * @return string
     */
    public function getMergeRef() : string
    {

        $result  = $this->getRef();
        $result .= ':';
        $result .= XlsxTools::convNumberToRef($this->col + $this->mergeCols - 1);
        $result .= ( $this->row + $this->mergeRows - 1 );

        return $result;
    }

    /**
     * Is Cell have Style.
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasStyle() : bool
    {

        return ! ( $this->style === null );
    }

    /**
     * Setter for Style.
     *
     * @param Style $style Style for this Cell.
     *
     * @since  v1.0
     * @return self
     */
    public function setStyle(Style $style) : self
    {

        $this->style = $style;

        return $this;
    }

    /**
     * Is Cell have Style.
     *
     * @param boolean $createIfNone Optional, true. If set to true create Style automatically if it is not present.
     *
     * @since  v1.0
     * @return boolean
     */
    public function getStyle(bool $createIfNone = true) : ?Style
    {

        // Create if it has to be done.
        if ($this->style === null && $createIfNone === true) {
            $this->style = new Style($this->xlsx);
        }

        return $this->style;
    }
}
