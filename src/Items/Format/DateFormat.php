<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items\Format;

use Przeslijmi\XlsxPeasant\Items\Format;

/**
 * Date format definition used in Style.
 */
class DateFormat extends Format
{

    /**
     * Construct.
     *
     * @since v1.0
     */
    public function __construct()
    {

        $this->setType('date');
    }

    /**
     * Return code of Format in XLSX language (syntax).
     *
     * @since  v1.0
     * @return string
     */
    public function getCode() : string
    {

        return 'yyyy\-mm\-dd;@';
    }

    /**
     * Getter for signature.
     *
     * @since  v1.0
     * @return string
     */
    public function getSignature() : string
    {

        // Lvd.
        $result = '';

        // Fill up.
        $result .= 'type:' . $this->getType();

        return $result;
    }
}
