<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

/**
 * Can't change set of columns - there is data already added.
 */
class TableChangeColumnForbiddenException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string $name Name of table that has data.
     *
     * @since v1.0
     */
    public function __construct(string $name)
    {

        // Lvd.
        $hint  = 'Trying to change Columns while data is already added to Table. ';
        $hint .= 'Delete data, add/change colums, insert data.';

        // Define.
        $this->addInfo('tableName', $name);
        $this->addHint($hint);
    }
}
