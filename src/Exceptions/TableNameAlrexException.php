<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Throwable;

/**
 * Generation ox XLSX file has failed.
 */
class TableNameAlrexException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string $name Name of table that is duplicated.
     */
    public function __construct(string $name)
    {

        // Lvd.
        $hint  = 'Trying to add second table with the same name `' . $name . '`. ';
        $hint .= 'Name of tables are unique in the entire XLSX.';

        // Define.
        $this->addInfo('name', $name);
        $this->addHint($hint);
    }
}
