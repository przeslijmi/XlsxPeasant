<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ObjectDonoexException;

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
     */
    public function __construct(string $tableName, $idOrName)
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
        $this->addHint($hint);
    }
}
