<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items;

use Przeslijmi\XlsxPeasant\Exceptions\NoSheetsException;
use Przeslijmi\XlsxPeasant\Exceptions\SheetDonoexException;
use Przeslijmi\XlsxPeasant\Exceptions\TableDonoexException;
use Przeslijmi\XlsxPeasant\Items;
use Przeslijmi\XlsxPeasant\Items\Sheet;
use Przeslijmi\XlsxPeasant\Items\Table;
use Przeslijmi\XlsxPeasant\Xlsx;

/**
 * Parent for all sheets inside Xlsx.
 */
class Book extends Items
{

    /**
     * Collection of Sheets.
     *
     * @var Sheet[]
     */
    private $sheets = [];

    /**
     * Collection of Tables.
     *
     * @var Table[]
     */
    private $tables = [];

    /**
     * Adds new Sheet to XLSX.
     *
     * @param string       $name Name of Sheet.
     * @param null|integer $id   Optional id of Sheet.
     *
     * @since  v1.0
     * @return Sheet
     */
    public function addSheet(string $name, ?int $id = null) : Sheet
    {

        // Create Sheet.
        $sheet = new Sheet($this->xlsx, $name, $id);
        $sheet->setBook($this);

        // Save Sheet.
        $this->sheets[] = $sheet;

        return $sheet;
    }

    /**
     * Getter for all Sheets.
     *
     * @param boolean $throwOnEmpty Optional, true. If set to true will throw when no Column is added.
     *
     * @since  v1.0
     * @throws NoSheetsException When collection is empty and throwing is on.
     * @return Sheet[]
     */
    public function getSheets(bool $throwOnEmpty = true) : array
    {

        // If collection is empty and throwing is on - throw.
        if ($throwOnEmpty === true && count($this->sheets) === 0) {
            throw new NoSheetsException();
        }

        return $this->sheets;
    }

    /**
     * Getter for one Sheet.
     *
     * @param integer $id Id of Sheet.
     *
     * @since  v1.0
     * @throws SheetDonoexException When Sheet with given id does not exists.
     * @return Sheet
     */
    public function getSheet(int $id) : Sheet
    {

        foreach ($this->sheets as $sheet) {
            if ($sheet->getId() === $id) {
                return $sheet;
            }
        }

        throw new SheetDonoexException($id);
    }

    /**
     * Getter for one Sheet by name.
     *
     * @param string $name Name of Sheet.
     *
     * @since  v1.0
     * @throws SheetDonoexException When Sheet with given id does not exists.
     * @return Sheet
     */
    public function getSheetByName(string $name) : Sheet
    {

        foreach ($this->sheets as $sheet) {
            if ($sheet->getName() === $name) {
                return $sheet;
            }
        }

        throw new SheetDonoexException($name);
    }

    /**
     * Checks if there is Sheet by name.
     *
     * @param string $name Name of Sheet.
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasSheetByName(string $name) : bool
    {

        foreach ($this->sheets as $sheet) {
            if ($sheet->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * Adds new Table to XLSX.
     *
     * @param string       $name Name of Table.
     * @param null|integer $id   Optional id of Table.
     *
     * @since  v1.0
     * @return Table
     */
    public function addTable(string $name, ?int $id = null) : Table
    {

        // Create Table.
        $table = new Table($this->xlsx, $name, $id);

        // Save Table.
        $this->tables[] = $table;

        return $table;
    }

    /**
     * Check if Book or Sheet has any Tables.
     *
     * @param null|Sheet $sheet Optional Sheet to narrow searching only to Tables from given Sheet.
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasTables(?Sheet $sheet = null) : bool
    {

        // If Sheet is given.
        if ($sheet !== null) {

            // Loop until you'll find first Table for given Sheet.
            foreach ($this->tables as $table) {
                if ($table->getSheet() === $sheet) {
                    return true;
                }
            }

            return false;
        }

        // Return for whole Book.
        return ( count($this->tables) > 0 );
    }

    /**
     * Getter for all Tables.
     *
     * @param null|Sheet $sheet Optional Sheet to narrow searching only to Tables from given Sheet.
     *
     * @since  v1.0
     * @return Table[]
     */
    public function getTables(?Sheet $sheet = null) : array
    {

        // If Sheet is given.
        if ($sheet !== null) {

            // Lvd.
            $result = [];

            // Add to result all Tables from given Sheet.
            foreach ($this->tables as $table) {
                if ($table->getSheet() === $sheet) {
                    $result[] = $table;
                }
            }

            return $result;
        }

        // Return for whole Book.
        return $this->tables;
    }

    /**
     * Return Table object from this book identified by its name.
     *
     * @param string $name Name of Table.
     *
     * @since  v1.0
     * @throws TableDonoexException When Table with this name does not exits.
     * @return Table
     */
    public function getTableByName(string $name) : Table
    {

        // Try to find and return.
        foreach ($this->tables as $table) {
            if ($table->getName() === $name) {
                return $table;
            }
        }

        throw new TableDonoexException($name);
    }

    /**
     * Checks if there is Table by name.
     *
     * @param string     $name  Name of Table.
     * @param null|Sheet $sheet Optional Sheet to narrow searching only to Tables from given Sheet.
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasTableByName(string $name, ?Sheet $sheet = null) : bool
    {

        // Lvd.
        $tables = $this->getTables($sheet);

        // Try to find and return.
        foreach ($tables as $table) {
            if ($table->getName() === $name) {
                return true;
            }
        }

        return false;
    }
}
