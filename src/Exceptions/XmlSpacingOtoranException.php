<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ParamOtoranException;
use Throwable;

/**
 * Ordered XML spacing is either below 0 or above 10. Both are wrong.
 */
class XmlSpacingOtoranException extends ParamOtoranException
{

    /**
     * Constructor.
     *
     * @param integer $spacing Ordered spacing value.
     *
     * @since v1.0
     */
    public function __construct(int $spacing)
    {

        // Lvd.
        $hint  = 'Spacing for XML file indentation (number of spaces to use as one indent) must not be ';
        $hint .= 'below 0 or above 10, but `' . $spacing . '` given.';

        // Define.
        $this->addInfo('paramName', 'xmlSpacing');
        $this->addInfo('range', '0>x>10');
        $this->addInfo('actualValue', (string) $spacing);
        $this->addInfo('hint', $hint);
    }
}
