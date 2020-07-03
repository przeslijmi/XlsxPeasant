<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;

/**
 * Parameter's given value is out of set (enum - not out of range [i .... j]).
 */
class NoSheetsException extends ClassFopException
{

    /**
     * Constructor.
     */
    public function __construct()
    {

        // Lvd.
        $hint  = 'Trying to generate XLSX with no Sheets. Call eg.: ';
        $hint .= '`$xlsx->getBook()->addSheet(\'name\');`';

        // Define.
        $this->addInfo('context', 'XlsxGeneration');
        $this->addHint($hint);
    }
}
