<?php declare(strict_types=1);

namespace Przeslijmi\XlsxGenerator\Items;

use Przeslijmi\XlsxGenerator\Items;
use Przeslijmi\XlsxGenerator\Items\Sheet;
use Przeslijmi\XlsxGenerator\Xlsx;

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
     * Adds new Sheet to XLSX.
     *
     * @param string $name Name of the Sheet.
     *
     * @since  v1.0
     * @return Sheet
     */
    public function addSheet(string $name) : Sheet
    {

        // Lvd.
        $index = 0;

        // Find max index.
        foreach ($this->sheets as $sheet) {
            $index = max($index, $sheet->getId());
        }

        // Increase index.
        ++$index;

        // Create Sheet.
        $sheet = new Sheet($this->xlsx, $index, $name);

        // Save Sheet.
        $this->sheets[] = $sheet;

        return $sheet;
    }

    /**
     * Getter for all Sheets.
     *
     * @return Sheet[]
     */
    public function getSheets() : array
    {

        return $this->sheets;
    }
}
