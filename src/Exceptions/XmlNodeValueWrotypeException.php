<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ValueWrotypeException;
use Throwable;

/**
 * Node value can be scalar, array or null - nothing else.
 */
class XmlNodeValueWrotypeException extends ValueWrotypeException
{

    /**
     * Constructor.
     *
     * @param string $nodeValueType Given type of node value.
     *
     * @since v1.0
     */
    public function __construct(string $nodeValueType)
    {

        // Lvd.
        $hint = 'Node value in XML must be scalar, array or null, but `' . $nodeValueType . '` was given.';

        // Define.
        $this->addInfo('valueName', 'xmlNodeValue');
        $this->addInfo('typeExpected', 'array, scalar, null');
        $this->addInfo('actualType', $nodeValueType);
        $this->addInfo('hint', $hint);
    }
}
