<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items;

use Przeslijmi\XlsxPeasant\Xlsx;

/**
 * Value format definition used in Style.
 */
abstract class Format
{

    /**
     * Type of format (number, date).
     *
     * @var string
     */
    private $type;

    /**
     * Getter for id of this format registered/used in given xlsx.
     *
     * It is different approach than in other Items because Format when is created is not connected
     * with exact given sheet. This connection is created later - on XML generation. It is when XLSX
     * file is set for this Format, id is calculated and therefore it can be returned.
     *
     * @param Xlsx $xlsx For which Xlsx id has to be given (one Format can be used in multiple Xlsx).
     *
     * @since  v1.0
     * @return integer
     */
    public function getIdForXlsx(Xlsx $xlsx) : int
    {

        return $xlsx->registerFormatsId($this);
    }

    /**
     * Setter for type.
     *
     * @param string $type Type of format (number, date).
     *
     * @since  v1.0
     * @return self
     */
    public function setType(string $type) : self
    {

        $this->type = $type;

        return $this;
    }

    /**
     * Getter for type.
     *
     * @since  v1.0
     * @return string
     */
    public function getType() : string
    {

        return $this->type;
    }
}
