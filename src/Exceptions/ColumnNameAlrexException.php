<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

/**
 * User is trying to add column with name that is already taken in this Table.
 */
class ColumnNameAlrexException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string         $tableName  Name of table in which Columns are present.
     * @param string         $columnName Name of column that is duplicated.
     * @param Exception|null $cause      Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $tableName, string $columnName, ?Exception $cause = null)
    {

        // Lvd.
        $hint  = 'In Table `' . $tableName . '` you\'re trying to add second Column ';
        $hint .= 'with the same name `' . $name . '`. ';
        $hint .= 'Name of Columns has to be unique in one Table.';

        // Define.
        $this->setCodeName('ColumnNameAlrexException');
        $this->addInfo('tableName', $tableName);
        $this->addInfo('columnName', $columnName);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('ColumnNameAlrexException', $cause);
        }
    }
}
