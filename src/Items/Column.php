<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items;

use Exception;
use Przeslijmi\Sivalidator\RegEx;
use Przeslijmi\XlsxPeasant\Exceptions\ColumnIdOtoranException;
use Przeslijmi\XlsxPeasant\Exceptions\ColumnNameAlrexException;
use Przeslijmi\XlsxPeasant\Exceptions\ColumnNameWrosynException;
use Przeslijmi\XlsxPeasant\Helpers\Tools as XlsxTools;
use Przeslijmi\XlsxPeasant\Items;
use Przeslijmi\XlsxPeasant\Items\Cell;
use Przeslijmi\XlsxPeasant\Items\ConditionalFormat;
use Przeslijmi\XlsxPeasant\Items\Format;
use Przeslijmi\XlsxPeasant\Items\Table;

/**
 * Column object - Table header.
 */
class Column extends Items
{

    /**
     * Id of Column.
     *
     * @var integer
     */
    private $id;

    /**
     * UUID of Column.
     *
     * @var string
     */
    private $uuid;

    /**
     * Name of Column.
     *
     * @var string
     */
    private $name;

    /**
     * Table object.
     *
     * @var Table
     */
    private $table;

    /**
     * Cell object.
     *
     * @var Cell
     */
    private $cell;

    /**
     * Which Format is used in this Table Column.
     *
     * @var Format
     */
    private $format;

    /**
     * Which Conditional Format is used in this Table Column.
     *
     * @var ConditionalFormat
     */
    private $conditionalFormat;

    /**
     * Constructor.
     *
     * @param Table   $table Parent Table object.
     * @param string  $name  Name of Column.
     * @param integer $id    Id of Column.
     *
     * @since v1.0
     */
    public function __construct(Table $table, string $name, ?int $id = null)
    {

        // Call Items.
        parent::__construct($table->getXlsx());

        // Set.
        $this->table = $table;
        $this->uuid  = XlsxTools::createUuid();
        $this->setId($id);
        $this->setName($name);

        // Create cell.
        $this->cell = $table->getSheet()->getCell(...$this->getRefs());
        $this->cell->setValue($name);
    }

    /**
     * Getter for parent Table.
     *
     * @since  v1.0
     * @return Table
     */
    public function getTable() : Table
    {

        return $this->table;
    }

    /**
     * Getter for Column id.
     *
     * @since  v1.0
     * @return integer
     */
    public function getId() : int
    {

        return $this->id;
    }

    /**
     * Getter for UUID.
     *
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
     * Setter for id.
     *
     * @param integer $id Id of Column.
     *
     * @since  v1.0
     * @throws ColumnIdOtoranException When ID is below 1.
     * @return self
     */
    private function setId(?int $id = null) : self
    {

        // Find id if not given.
        if ($id === null) {
            $id = $this->findSpareId($this->getTable()->getColumns(false));
        }

        // Check.
        if ($id < 1) {
            throw new ColumnIdOtoranException($this->getTable()->getName(), $id);
        }

        // Set.
        $this->id = $id;

        return $this;
    }

    /**
     * Setter for name.
     *
     * @param string $name Name of Column.
     *
     * @since  v1.0
     * @throws ColumnNameWrosynException When name has wrong syntax.
     * @throws ColumnNameAlrexException  When name in Book already exists.
     * @return self
     */
    private function setName(string $name) : self
    {

        // Test.
        try {
            // Rule out too long names.
            RegEx::ifNotMatches($name, '/^(.){256,}$/');
        } catch (Exception $exc) {
            throw new ColumnNameWrosynException($name, $exc);
        }

        // Test duplication of names of Tables in whole Xlsx.
        foreach ($this->getTable()->getColumns(false) as $columnInTable) {

            // Throw.
            if ($columnInTable->getName() === $name) {
                throw new ColumnNameAlrexException($this->getTable()->getName(), $name);
            }
        }

        // Set.
        $this->name = $name;

        return $this;
    }

    /**
     * Setter for Format object.
     *
     * @param Format $format Format object to use in this Table Column.
     *
     * @since  v1.0
     * @return null|Format
     */
    public function setFormat(?Format $format) : self
    {

        $this->format = $format;

        return $this;
    }

    /**
     * Getter for Format object for this Table Column - if is defined.
     *
     * @since  v1.0
     * @return null|Format
     */
    public function getFormat() : ?Format
    {

        return $this->format;
    }

    /**
     * Setter for Conditional Format object..
     *
     * @param ConditionalFormat $conditionalFormat ConditionalFormat object to use in this Table Column.
     *
     * @since  v1.0
     * @return null|ConditionalFormat
     */
    public function setConditionalFormat(?ConditionalFormat $conditionalFormat) : self
    {

        $this->conditionalFormat = $conditionalFormat;

        return $this;
    }

    /**
     * Getter for Conditional Format object for this Table Column - if is defined.
     *
     * @since  v1.0
     * @return null|ConditionalFormat
     */
    public function getConditionalFormat() : ?ConditionalFormat
    {

        return $this->conditionalFormat;
    }

    /**
     * Sets width of this Column.
     *
     * @param null|float $width Optional float to be set. If not given original width is restored.
     *
     * @return self
     */
    public function setWidth(?float $width = null) : self
    {

        $this->cell->setColWidth($width);

        return $this;
    }

    /**
     * Return row and col id for this Column.
     *
     * @since  v1.0
     * @return int[2]
     */
    private function getRefs() : array
    {

        $row = ( $this->table->getRefs()[0] );
        $col = ( $this->table->getRefs()[1] + $this->id - 1 );

        return [ $row, $col ];
    }
}
