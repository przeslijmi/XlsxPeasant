<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant;

use Przeslijmi\XlsxPeasant\Xlsx;
use Przeslijmi\XlsxPeasant\Xmls;

/**
 * Parent object for Book, Sheet, Cell, Style objects etc.
 */
class Items
{

    /**
     * XLSX object.
     *
     * @var Xlsx
     */
    protected $xlsx;

    /**
     * Constructor.
     *
     * @param Xlsx $xlsx XLSX object.
     *
     * @since v1.0
     */
    public function __construct(Xlsx $xlsx)
    {

        $this->xlsx = $xlsx;
    }

    /**
     * Getter for Xlsx.
     *
     * @since  v1.0
     * @return Xlsx
     */
    public function getXlsx() : Xlsx
    {

        return $this->xlsx;
    }

    /**
     * Looks for spare id in collection of Items.
     *
     * @param Items[] $arrayOfItems Array of items.
     * @param integer $start        Id to be returned when Items[] is empty.
     *
     * @since  v1.0
     * @throws LookingForSpareIdLoopOtoranException When more 10000 tryes failed.
     * @return integer
     */
    protected function findSpareId(array $arrayOfItems, int $start = 1) : int
    {

        // Shortcut.
        if (count($arrayOfItems) === 0) {
            return $start;
        }

        // Lvd.
        $i     = $start;
        $found = null;

        // Do the job.
        do {

            // If this is spare - return it.
            foreach ($arrayOfItems as $item) {
                if ($item->getId() === $i) {
                    ++$i;
                    continue 2;
                }
            }

            return $i;

        } while ($i < 10000);

        throw new LookingForSpareIdLoopOtoranException();
    }
}
