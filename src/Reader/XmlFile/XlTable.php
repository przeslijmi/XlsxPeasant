<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Reader\XmlFile;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Sexceptions\Exceptions\KeyDonoexException;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Sexceptions\Exceptions\ObjectDonoexException;
use Przeslijmi\XlsxPeasant\Helpers\Tools as XlsxTools;
use Przeslijmi\XlsxPeasant\Reader;
use Przeslijmi\XlsxPeasant\Reader\XmlFile;
use Przeslijmi\XlsxPeasant\Reader\XmlFile\XlWorksheet;
use Throwable;

/**
 * Table XML file as object.
 */
class XlTable extends XmlFile
{

    /**
     * Sheet's id `id="3"`.
     *
     * @var integer
     */
    private $id;

    /**
     * Sheet's order number `r:id="rId2"`.
     *
     * @var integer
     */
    private $number;

    /**
     * Sheet's name.
     *
     * @var string
     */
    private $name;

    /**
     * Worksheet that uses this Table.
     *
     * @var XlWorksheet
     */
    private $xlWorksheet;

    /**
     * List of Columns inside this Table.
     *
     * @var array
     */
    private $columns = [];

    /**
     * List of cells that was read by this table to avoid reading them again.
     *
     * @var array
     */
    private $cellsRead = [];

    /**
     * Constructor.
     *
     * @param string $fileUri Uri of XML file.
     * @param Reader $reader  Parent Reader instance.
     *
     * @since  v1.0
     * @throws ClassFopException When creating table failed.
     */
    public function __construct(string $fileUri, Reader $reader)
    {

        try {

            // Save reader to parent.
            parent::__construct($fileUri, $reader);

            // Define this object.
            $this->setIdAndNumber();
            $this->setName();
            $this->setColumns();
            $this->setXlWorksheet();

        } catch (Throwable $thr) {
            throw (new ClassFopException('creatingReaderTable', $thr))
                ->addObjectInfos($reader);
        }
    }

    /**
     * Getter for `id`.
     *
     * @since  v1.0
     * @return integer
     */
    public function getId() : int
    {

        return $this->id;
    }

    /**
     * Getter for `number`.
     *
     * @since  v1.0
     * @return integer
     */
    public function getNumber() : int
    {

        return $this->number;
    }

    /**
     * Getter for `name`.
     *
     * @since  v1.0
     * @return string
     */
    public function getName() : string
    {

        return $this->name;
    }

    /**
     * Getter for `xlWorksheet`.
     *
     * @since  v1.0
     * @return XlWorkSheet
     */
    public function getXlWorksheet() : XlWorkSheet
    {

        return $this->xlWorksheet;
    }

    /**
     * Get this Table XML node.
     *
     * @since  v1.0
     * @return object XML node.
     */
    public function getTableNode() : object
    {

        return $this->contents->getElementsByTagName('table')->item(0);
    }

    /**
     * Get number of first row for this Table (row 1 equals 1 [not 0]).
     *
     * @since  v1.0
     * @return integer
     */
    public function getFirstRow() : int
    {

        // Convert A1:D3 to D3.
        list($startRef,) = explode(':', $this->getTableNode()->getAttribute('ref'));

        // Convert A1 to 1.
        $startRow = (int) ( preg_replace('/([A-Z])/', '', $startRef) );

        return $startRow;
    }

    /**
     * Get number of last row for this Table (row 1 equals 1 [not 0]).
     *
     * @since  v1.0
     * @return integer
     */
    public function getLastRow() : int
    {

        // Convert A1:D3 to D3.
        list(,$endRef) = explode(':', $this->getTableNode()->getAttribute('ref'));

        // Convert D3 to 3.
        $endRow = (int) ( preg_replace('/([A-Z])/', '', $endRef) );

        return $endRow;
    }

    /**
     * Get number of first column for this Table (column A equals 1).
     *
     * @since  v1.0
     * @return integer
     */
    public function getFirstCol() : int
    {

        // Convert A1:D3 to A1.
        list($startRef,) = explode(':', $this->getTableNode()->getAttribute('ref'));

        // Convert A1 to 1.
        $startCol = preg_replace('/([0-9])/', '', $startRef);

        return XlsxTools::convRefToNumber($startCol);
    }

    /**
     * Get number of last column for this Table (column A equals 1).
     *
     * @since  v1.0
     * @return integer
     */
    public function getLastCol() : int
    {

        // Convert A1:D3 to D3.
        list(,$endRef) = explode(':', $this->getTableNode()->getAttribute('ref'));

        // Convert D3 to 4.
        $endCol = preg_replace('/([0-9])/', '', $endRef);

        return XlsxTools::convRefToNumber($endCol);
    }

    /**
     * Gets column with given id number.
     *
     * @param integer $number Desired order number of column.
     *
     * @since  v1.0
     * @throws KeyDonoexException    When column with given `$number` does not exists.
     * @throws ObjectDonoexException When column with given `$number` does not exists.
     * @return string
     */
    public function getColumn(int $number) : string
    {

        // Try to reach that.
        if (isset($this->columns[$number]) === true) {
            return $this->columns[$number];
        }

        // Throw.
        try {
            throw new KeyDonoexException('columnsInXlsx', array_keys($this->columns), (string) $number);
        } catch (Throwable $thr) {
            throw (new ObjectDonoexException('columnInXlsxFile', $thr))
                ->addInfo('tableName', $this->name)
                ->addInfo('columnNumber', (string) $number)
                ->addObjectInfos($this->getReader());
        }
    }

    /**
     * Getter for `columns`.
     *
     * @return array
     */
    public function getColumns() : array
    {

        return $this->columns;
    }

    /**
     * Get whole data for this Table.
     *
     * ## Return example
     * ```
     * [
     *   1 => [
     *     1 => 'Header 1',
     *     2 => 'Header 2',
     *   ],
     *   2 => [
     *     1 => 'Data for first row in column 1',
     *     2 => 'Data for first row in column 2',
     *   ],
     *   3 => [
     *     1 => 'Data for second row in column 1',
     *     2 => 'Data for second row in column 2',
     *   ]
     * ]
     * ```
     *
     * @since  v1.0
     * @throws MethodFopException If getting data is impossible.
     * @return array
     */
    public function getData() : array
    {

        // Lvd.
        $result       = [];
        $firstDataCol = $this->getFirstCol();
        $lastDataCol  = $this->getLastCol();
        $firstDataRow = ( $this->getFirstRow() + 1 );
        $lastDataRow  = $this->getLastRow();
        $rowNo        = -1;

        // Try.
        try {

            // Scan each row.
            for ($r = $firstDataRow; $r <= $lastDataRow; ++$r) {

                // Next row.
                ++$rowNo;

                // Scan each cell in row.
                for ($c = $firstDataCol; $c <= $lastDataCol; ++$c) {

                    // Get value for cell.
                    $value = ( $this->xlWorksheet->getCellValue($r, $c) ?? '' );

                    // Get column name.
                    $columnName = $this->getColumn($c);

                    // Add to result.
                    $result[$rowNo][$columnName] = $value;

                    // Add refs of this cell to save it to cellsReadByTables (row, col).
                    $this->cellsRead[] = [ $r, $c ];
                }
            }//end for
        } catch (Throwable $thr) {
            throw (new MethodFopException('readDataFromXlsxTable', $thr))
                ->addInfo('tableName', $this->name)
                ->addObjectInfos($this->getReader());
        }//end try

        return $result;
    }

    /**
     * Get list of cells that are already read (included) inside this Table (not to read them again).
     *
     * ## Return example
     * ```
     * [ [ 1, 1 ], [ 1, 2 ], [ 1, 3 ], [ 1, 4 ] ]
     * ```
     *
     * @since  v1.0
     * @return array
     */
    public function getCellsRead() : array
    {

        return $this->cellsRead;
    }

    /**
     * Setter for `id`.
     *
     * @since  v1.0
     * @return self
     */
    private function setIdAndNumber() : self
    {

        // Lvd.
        $fileUri = str_replace('/', '\\', $this->getFileUri());

        // Get number.
        $number = substr($fileUri, ( strrpos($fileUri, '\\tables\\table') + 13 ));
        $number = (int) ( substr($number, 0, -4) );

        // Get id.
        $id = (int) ( $this->getTableNode()->getAttribute('id') );

        // Save.
        $this->number = $number;
        $this->id     = $id;

        return $this;
    }

    /**
     * Setter for `name`.
     *
     * @since  v1.0
     * @return self
     */
    private function setName() : self
    {

        // Lvd.
        $name = $this->getTableNode()->getAttribute('name');

        // Save.
        $this->name = $name;

        return $this;
    }

    /**
     * Setter for `xlWorksheet`.
     *
     * @since  v1.0
     * @throws MethodFopException When is unable to find worksheet.
     * @return self
     */
    private function setXlWorksheet() : self
    {

        // Try to find which worksheet has this Table.
        foreach ($this->getReader()->getXlWorksheets() as $xlWorksheet) {

            if ($xlWorksheet->doYouUseTable($this->getNumber()) === true) {

                // Save.
                $this->xlWorksheet = $xlWorksheet;

                // End.
                return $this;
            }
        }

        throw (new MethodFopException('findWorksheetForTable'))->addObjectInfos($this->getReader());
    }

    /**
     * Setter for `columns`.
     *
     * @since  v1.0
     * @return self
     */
    private function setColumns() : self
    {

        // Lvd.
        $inode = 0;

        // Reset.
        $this->columns = [];

        // Scan all <tableColumn> nodes.
        foreach ($this->contents->getElementsByTagName('tableColumn') as $colNode) {

            // Lvd.
            $cellNumber = ( $this->getFirstCol() + ( $inode++ ) );

            // Get refs of this header to save it to cellsReadByTables (row, col).
            $this->cellsRead[] = [ $this->getFirstRow(), $cellNumber ];

            // Save to Columns.
            $this->columns[$cellNumber] = $colNode->getAttribute('name');
        }

        return $this;
    }
}
