<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items\Collections;

use Przeslijmi\XlsxPeasant\Items;
use Przeslijmi\XlsxPeasant\Items\Cell;
use Przeslijmi\XlsxPeasant\Items\Row;
use Przeslijmi\XlsxPeasant\Xlsx;

/**
 * Collection of shared strings.
 */
class SharedStrings extends Items
{

    /**
     * Actual collection.
     *
     * @var array
     */
    private $index = [];

    /**
     * Collection index counter.
     *
     * @var integer
     */
    private $lastIndex = -1;

    /**
     * Register Cell contents (ValuePart[]) in shared strings dictionary.
     *
     * @param Cell $cell Cell with content to register.
     *
     * @return void
     */
    public function registerCell(Cell $cell) : void
    {

        // Lvd.
        $signature = '';

        // Add signature for value of every Cell.
        foreach ($cell->getValue() as $part) {
            $signature .= $part->getSignature();
        }

        // Add if not exists.
        if (isset($this->index[$signature]) === false) {

            // Increase index counter.
            ++$this->lastIndex;

            // Add.
            $this->index[$signature] = [
                'valueParts' => $cell->getValue(),
                'id'         => $this->lastIndex,
            ];
        }

        // Call back to Cell to set ID.
        $cell->setShardStringId($this->index[$signature]['id']);
    }

    /**
     * Register string contents in shared strings dictionary.
     *
     * @param string $value String to be registered.
     *
     * @return integer
     */
    public function registerValue(string $value) : int
    {

        // Add if not exists.
        if (isset($this->index[$value]) === false) {

            // Increase index counter.
            ++$this->lastIndex;

            // Add.
            $this->index[$value] = [
                'valueParts' => $value,
                'id'         => $this->lastIndex,
            ];
        }

        return $this->index[$value]['id'];
    }

    /**
     * Get whole index but without signatures - only ValuePart[].
     *
     * @return array Array of ValuePart[].
     */
    public function getIndex() : array
    {

        // Lvd.
        $result = [];

        // Create.
        foreach ($this->index as $valueInfo) {
            $result[] = $valueInfo['valueParts'];
        }

        return $result;
    }
}
