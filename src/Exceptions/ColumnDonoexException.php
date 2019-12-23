<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ObjectDonoexException;
use Throwable;

/**
 * Can't find Column by given ID or name.
 */
class ColumnDonoexException extends ObjectDonoexException
{

    /**
     * Constructor.
     *
     * @param string         $tableName Name of Table in which Column is searched for and missing.
     * @param integer|string $idOrName  Id or name of Column.
     * @param Throwable|null $cause     Throwable that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $tableName, $idOrName, ?Throwable $cause = null)
    {

        // Lvd.
        if (is_int($idOrName) === true) {
            $hint = 'Trying to get Column by ID - but Column with this ID does not exists.';
        } else {
            $hint = 'Trying to get Column by Name - but Column with this Name does not exists.';
        }

        // Define.
        $this->addInfo('tableName', $tableName);
        $this->addInfo('columnIdOrName', (string) $idOrName);
        $this->addInfo('context', 'GetXlsxTableColumn');
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('ColumnDonoexException', $cause);
        }
    }
}
