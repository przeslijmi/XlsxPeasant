<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items\Format;

use Przeslijmi\XlsxPeasant\Items\Format;

/**
 * Hidden format definition used in Style.
 */
class HiddenFormat extends Format
{

    /**
     * Construct.
     */
    public function __construct()
    {

        $this->setType('hidden');
    }

    /**
     * Return code of Format in XLSX language (syntax).
     *
     * @return string
     */
    public function getCode() : string
    {

        return ';;;';
    }

    /**
     * Getter for signature.
     *
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
