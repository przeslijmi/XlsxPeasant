<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items\ConditionalFormat;

use Przeslijmi\XlsxPeasant\Items\ConditionalFormat;

/**
 * Data bar conditional format definition used in Style.
 */
class DataBar extends ConditionalFormat
{

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
        $result .= 'conditionalFormat: dataBar';

        return $result;
    }
}
