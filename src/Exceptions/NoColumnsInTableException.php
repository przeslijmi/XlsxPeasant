<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;

/**
 * Parameter's given value is out of set (enum - not out of range [i .... j]).
 */
class NoColumnsInTableException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @since v1.0
     */
    public function __construct()
    {

        // Lvd.
        $hint  = 'Trying to generate XLSX table that has no columns. Call eg.: ';
        $hint .= '`$table->addColumns([ \'a\', \'b\' ]);`';

        // Define.
        $this->addInfo('context', 'XlsxGeneration');
        $this->addHint($hint);
    }
}
