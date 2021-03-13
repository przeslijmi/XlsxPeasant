<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items;

use Przeslijmi\XlsxPeasant\Exceptions\CellValueWrotypeException;
use Przeslijmi\XlsxPeasant\Exceptions\RefWrosynException;
use Przeslijmi\XlsxPeasant\Exceptions\SetValueToMergedCellConflictException;
use Przeslijmi\XlsxPeasant\Helpers\Tools as XlsxTools;
use Przeslijmi\XlsxPeasant\Items;
use Przeslijmi\XlsxPeasant\Items\Color;
use Przeslijmi\XlsxPeasant\Items\Fill;
use Przeslijmi\XlsxPeasant\Items\Sheet;
use Przeslijmi\XlsxPeasant\Items\Style;
use Przeslijmi\XlsxPeasant\Items\Table;
use Przeslijmi\XlsxPeasant\Items\ValuePart;

class Row extends Items
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

    private $data;
    private $styles;

    /**
     * Construct.
     *
     * @param Sheet   $sheet  Parent Sheet object.
     * @param integer $row    Row id of this cell (starting with 1).
     *
     * @throws RefWrosynException When row or col are below 1.
     */
    public function __construct(Sheet $sheet, int $row)
    {

        // Check.
        if ($row < 1) {
            throw new RefWrosynException('Row in Sheet', $sheet->getName(), $row, $col);
        }

        // Call Items.
        parent::__construct($sheet->getXlsx());

        // Lvd.
        $this->sheet  = $sheet;
        $this->row    = $row;
    }

    public function setTable(Table $table) : self
    {

        $this->table = $table;

        return $this;
    }

    public function setStyles(array $styles) : self
    {

        $this->styles = $styles;

        return $this;
    }

    public function setContents(array $data) : self
    {

        // Create cache of Columns.
        $columnsCache = [];
        foreach ($this->table->getColumns() as $column) {
            $columnsCache[$column->getName()] = $column;
        }

        // Serve each column in row.
        foreach ($data as $columnName => $value) {

            if (isset($columnsCache[$columnName]) === false) {
                continue;
            }

            // Lvd.
            $column = $columnsCache[$columnName];
            $colID  = ( $this->table->getRefs()[1] + $column->getId() - 1 );
            $ssId   = null;

            if (is_string($value) === true && strlen($value) > 0) {
                $ssId = $this->getXlsx()->getSharedStrings()->registerValue($value);
            }

            // Save to data.
            $this->data[] = [
                'colID' => $colID,
                'colRef' => XlsxTools::convNumberToRef($colID),
                'value' => $value,
                'sharedStringsId' => ( $ssId ?? null ),
                'style' => $this->styles[$column->getId()],
            ];
        }

        return $this;
    }

    public function getRowId() : int
    {

        return $this->row;
    }

    public function getData() : array
    {

        return $this->data;
    }
}
