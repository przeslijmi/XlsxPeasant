<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items;

use Exception;
use Przeslijmi\Sivalidator\RegEx;
use Przeslijmi\XlsxPeasant\Exceptions\ColumnDonoexException;
use Przeslijmi\XlsxPeasant\Exceptions\NoColumnsInTableException;
use Przeslijmi\XlsxPeasant\Exceptions\RefWrosynException;
use Przeslijmi\XlsxPeasant\Exceptions\TableChangeColumnForbiddenException;
use Przeslijmi\XlsxPeasant\Exceptions\TableCreationFopException;
use Przeslijmi\XlsxPeasant\Exceptions\TableIdOtoranException;
use Przeslijmi\XlsxPeasant\Exceptions\TableNameAlrexException;
use Przeslijmi\XlsxPeasant\Exceptions\TableNameWrosynException;
use Przeslijmi\XlsxPeasant\Items;
use Przeslijmi\XlsxPeasant\Items\Column;
use Przeslijmi\XlsxPeasant\Helpers\Tools as XlsxTools;
use Przeslijmi\XlsxPeasant\Xlsx;
use Przeslijmi\XlsxPeasant\Xmls\XlTable;

/**
 * Table object - child of Book.
 */
class Table extends Items
{

    /**
     * Id of Table.
     *
     * @var integer
     */
    private $id;

    /**
     * UUID of Table.
     *
     * @var string
     */
    private $uuid;

    /**
     * Name of Table.
     *
     * @var string
     */
    private $name;

    /**
     * List of columns with ID (number) as a key.
     *
     * @var Column[]
     */
    private $columns = [];

    /**
     * List of rows of data in table.
     *
     * @var scalar[][]
     */
    private $rows = [];

    /**
     * Counter of rows to use while pouring data.
     *
     * @var integer
     */
    private $rowsCounter = -1;

    /**
     * Sheet parent object.
     *
     * @var Sheet
     */
    private $sheet;

    /**
     * Left top corner location refs of Table (row, col).
     *
     * @var int[2]
     */
    private $refs = [ 1, 1 ];

    /**
     * XlTable XML object.
     *
     * @var XlTable
     */
    private $xml;

    /**
     * Constructor.
     *
     * @param Xlsx         $xlsx Parent XLSX object.
     * @param string       $name Name of Table.
     * @param null|integer $id   Optional Id of Table.
     *
     * @since  v1.0
     * @throws TableCreationFopException When Table creation somehow failed.
     */
    public function __construct(Xlsx $xlsx, string $name, ?int $id = null)
    {

        try {

            // Set XLSX.
            parent::__construct($xlsx);

            // Set most important.
            $this->setId($id);
            $this->setName($name);

            // Set.
            $this->xml  = new XlTable($this);
            $this->uuid = XlsxTools::createUuid();

        } catch (Exception $exc) {
            throw new TableCreationFopException($name, $exc);
        }
    }

    /**
     * Tells generator where to put data from this Table - indicates left top corner of Table.
     *
     * @param Sheet   $sheet Sheet parent object.
     * @param integer $row   Optional, 1. Row for left top corner location refs of Table (starting at 1).
     * @param integer $col   Optional, 1. Col for left top corner location refs of Table (starting at 1).
     *
     * @since  v1.0
     * @throws RefWrosynException When row or col are below 1.
     * @return self
     */
    public function useAt(Sheet $sheet, int $row = 1, int $col = 1) : self
    {

        // Check.
        if ($row < 1 || $col < 1) {
            throw new RefWrosynException('table', $this->name, $row, $col);
        }

        // Save.
        $this->sheet   = $sheet;
        $this->refs[0] = $row;
        $this->refs[1] = $col;

        return $this;
    }

    /**
     * Setter for name.
     *
     * @param string $name Name of Table.
     *
     * @since  v1.0
     * @throws TableNameWrosynException When name has wrong syntax.
     * @throws TableNameAlrexException  When name in Book already exists.
     * @return self
     */
    public function setName(string $name) : self
    {

        // Test.
        try {

            // Proper chars, at least 2 chars length.
            RegEx::ifMatches($name, '/^([_\\\\A-Z]){1}([_\\\\A-Z0-9.]){1,}$/i');

            // Rule out A1, C2, R2C2, Z3:ZZ55.
            RegEx::ifNotMatches($name, '/^(([A-Z]){0,2}(\\d)+)+(:)?(([A-Z])+(\\d)+)?$/i');

            // Rule out too long names.
            RegEx::ifNotMatches($name, '/^(.){256,}$/');
        } catch (Exception $exc) {
            throw new TableNameWrosynException($name, $exc);
        }

        // Test duplication of names of Tables in whole Xlsx.
        foreach ($this->getXlsx()->getBook()->getTables() as $tableInBook) {

            // Throw.
            if ($tableInBook->getName() === $name) {
                throw new TableNameAlrexException($name);
            }
        }

        // Set.
        $this->name = $name;

        return $this;
    }

    /**
     * Setter for id.
     *
     * @param integer $id Id of Table.
     *
     * @since  v1.0
     * @throws TableIdOtoranException When ID is below 1.
     * @return self
     */
    public function setId(?int $id = null) : self
    {

        // Find id if not given.
        if ($id === null) {
            $id = $this->findSpareId($this->getXlsx()->getBook()->getTables());
        }

        // Check.
        if ($id < 1) {
            throw new TableIdOtoranException($id);
        }

        // Set.
        $this->id = $id;

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
     * Getter for uuid.
     *
     * @since  v1.0
     * @return string
     */
    public function getUuid() : string
    {

        return $this->uuid;
    }

    /**
     * Getter for name.
     *
     * @since  v1.0
     * @return string
     */
    public function getName() : string
    {

        return $this->name;
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
     * Getter for XlTable XML.
     *
     * @since  v1.0
     * @return XlTable
     */
    public function getXml() : XlTable
    {

        return $this->xml;
    }

    /**
     * Creates and adds one Column to this Table.
     *
     * @param string       $name Name of Column.
     * @param integer|null $id   Optional id of Column.
     *
     * @since  v1.0
     * @throws TableChangeColumnForbiddenException When trying to change Columns after inserting data to Table.
     * @return Column
     */
    public function addColumn(string $name, ?int $id = null) : Column
    {

        // Check.
        if (count($this->rows) > 0) {
            throw new TableChangeColumnForbiddenException($this->getName());
        }

        // Create and save.
        $column          = new Column($this, $name, $id);
        $this->columns[] = $column;

        return $column;
    }

    /**
     * Creates and adds many Columns to this Table.
     *
     * ## Usage example
     * ```
     * $table->addColumns([ 'firstname', 'surname', 'occupation', 'age' ]);
     * ```
     *
     * @param array $columns Array of Columns names.
     *
     * @since  v1.0
     * @return self
     */
    public function addColumns(array $columns) : self
    {

        // Create and save every one.
        foreach ($columns as $columnName) {
            $this->addColumn($columnName);
        }

        return $this;
    }

    /**
     * Getter for all Columns.
     *
     * @param boolean $throwOnEmpty Optional, true. If set to true will throw when no Column is added.
     *
     * @since  v1.0
     * @throws NoColumnsInTableException When collection is empty and throwing is on.
     * @return Column[]
     */
    public function getColumns(bool $throwOnEmpty = true) : array
    {

        // If collection is empty and throwing is on - throw.
        if ($throwOnEmpty === true && count($this->columns) === 0) {
            throw new NoColumnsInTableException();
        }

        return $this->columns;
    }

    /**
     * Gets Column by its name.
     *
     * @param string $name Name of Column.
     *
     * @since  v1.0
     * @throws ColumnDonoexException When there is no Column with given name.
     * @return Column
     */
    public function getColumnByName(string $name) : Column
    {

        // Search.
        foreach ($this->columns as $column) {
            if ($column->getName() === $name) {
                return $column;
            }
        }

        throw new ColumnDonoexException($this->getName(), $name);
    }

    /**
     * Gets Column by its id.
     *
     * @param integer $id Id of Column.
     *
     * @since  v1.0
     * @throws ColumnDonoexException When there is no Column with given id.
     * @return Column
     */
    public function getColumnById(int $id) : Column
    {

        // Search.
        foreach ($this->columns as $column) {
            if ($column->getId() === $id) {
                return $column;
            }
        }

        throw new ColumnDonoexException($this->getName(), $id);
    }

    /**
     * Count how many columns there are.
     *
     * @since  v1.0
     * @return integer
     */
    public function countColumns() : int
    {

        return count($this->columns);
    }

    /**
     * Add contents (data) to table.
     *
     * ## Usage example
     * ```
     * $table->addData([
     *     [
     *         'name' => 'John',
     *         'age' => 28,
     *         'department' => 'AID',
     *     ],
     *     [
     *         'name' => 'Johnny',
     *         'age' => 22,
     *         'department' => 'SFD',
     *     ],
     * ]);
     * ```
     *
     * @param array $rows See example.
     *
     * @since  v1.0
     * @throws ColumnDonoexException When Column called in data does not exists.
     * @return self
     */
    public function addData(array $rows) : self
    {

        // Create cache of Columns.
        $columnsCache = [];
        foreach ($this->getColumns() as $column) {
            $columnsCache[$column->getName()] = $column;
        }

        // Serve each row.
        foreach ($rows as $row) {

            // Increase row counter.
            ++$this->rowsCounter;

            // Serve each column in row.
            foreach ($row as $columnName => $value) {

                // Throw on nonexisting column.
                if (isset($columnsCache[$columnName]) === false) {
                    throw new ColumnDonoexException($this->getName(), $columnName);
                }

                // Lvd.
                $column = $columnsCache[$columnName];
                $rowRef = ( $this->refs[0] + 1 + $this->rowsCounter );
                $colRef = ( $this->refs[1] + $column->getId() - 1 );

                // Add Cell.
                $cell = $this->getSheet()->getCell($rowRef, $colRef);
                $cell->setValue($value);

                // Add number format for this Cell.
                if (( $format = $column->getFormat() ) !== null) {
                    $cell->getStyle()->setFormat($format);
                }

                // Add conditional format for this Cell.
                if (( $conditionalFormat = $column->getConditionalFormat() ) !== null) {
                    $cell->getStyle()->setConditionalFormat($conditionalFormat);
                }
            }//end foreach

            // Save rows for future reference.
            $this->rows[] = $row;
        }//end foreach

        return $this;
    }

    /**
     * Set contents (data) for table (deleting previous content).
     *
     * ## Usage example
     * ```
     * $table->setData([
     *     [
     *         'name' => 'John',
     *         'age' => 28,
     *         'department' => 'AID',
     *     ],
     *     [
     *         'name' => 'Johnny',
     *         'age' => 22,
     *         'department' => 'SFD',
     *     ],
     * ]);
     * ```
     *
     * @param array $rows See example.
     *
     * @since  v1.0
     * @return self
     */
    public function setData(array $rows) : self
    {

        // Delete current contents.
        $this->rows = [];

        // Add new contents.
        $this->addData($rows);

        return $this;
    }

    /**
     * Getter for all rows of data of table.
     *
     * @since  v1.0
     * @return array
     */
    public function getData() : array
    {

        return $this->rows;
    }

    /**
     * Returns number of rows in data for this Table.
     *
     * @since  v1.0
     * @return integer
     */
    public function countRows() : int
    {

        return count($this->rows);
    }

    /**
     * Returns array with refs to this Table [ row, col ].
     *
     * @since  v1.0
     * @return integer[2]
     */
    public function getRefs() : array
    {

        return $this->refs;
    }

    /**
     * Returns string with dimension refs to this Table, eg. A1:C4.
     *
     * @since  v1.0
     * @return string
     */
    public function getDimensionRef() : string
    {

        // Lvd.
        $numberOfColumns = count($this->getColumns());
        $firstRowNum     = $this->getRefs()[0];
        $firstColNum     = $this->getRefs()[1];
        $numOfRows       = ( ( count($this->rows) > 0 ) ? count($this->rows) : 1 );

        // Count last row and col.
        $lastColNum = ( $firstColNum + max(1, count($this->columns)) - 1 );
        $lastRowNum = ( $firstRowNum + $numOfRows );

        // Define final result.
        $result  = XlsxTools::convNumberToRef($firstColNum);
        $result .= $firstRowNum;
        $result .= ':';
        $result .= XlsxTools::convNumberToRef($lastColNum);
        $result .= $lastRowNum;

        return $result;
    }
}
