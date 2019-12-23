<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Throwable;

/**
 * Can't change set of columns - there is data already added.
 */
class TableChangeColumnForbiddenException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string         $name  Name of table that has data.
     * @param Throwable|null $cause Throwable that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $name, ?Throwable $cause = null)
    {

        // Lvd.
        $hint  = 'Trying to change Columns while data is already added to Table. ';
        $hint .= 'Delete data, add/change colums, insert data.';

        // Define.
        $this->addInfo('tableName', $name);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('TableChangeColumnForbiddenException', $cause);
        }
    }
}
