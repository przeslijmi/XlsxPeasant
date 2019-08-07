<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;

/**
 * Parameter's given value is out of set (enum - not out of range [i .... j]).
 */
class NoColumnsInTableException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param Exception|null $cause Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(?Exception $cause = null)
    {

        // Lvd.
        $hint  = 'Trying to generate XLSx table that has no columns. Call eg.: ';
        $hint .= '`$table->addColumns([ \'a\', \'b\' ]);`';

        // Define.
        $this->setCodeName('NoColumnsInTableException');
        $this->addInfo('context', 'XlsxGeneration');
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('NoColumnsInTableException', $cause);
        }
    }
}
