<?php declare(strict_types=1);

namespace Przeslijmi\XlsxGenerator;

use Przeslijmi\XlsxGenerator\Xlsx;
use Przeslijmi\XlsxGenerator\Xmls;

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
     * Getter for Xmls.
     *
     * @since  v1.0
     * @return Xmls
     */
    public function getXmls() : Xmls
    {

        return $this->xlsx->getXmls();
    }
}
