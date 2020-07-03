<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

/**
 * User is trying to add column with name that is already taken in this Table.
 */
class ColumnNameAlrexException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string $tableName  Name of table in which Columns are present.
     * @param string $columnName Name of column that is duplicated.
     */
    public function __construct(string $tableName, string $columnName)
    {

        // Lvd.
        $hint  = 'In Table `' . $tableName . '` you\'re trying to add second Column ';
        $hint .= 'with the same name `' . $columnName . '`. ';
        $hint .= 'Name of Columns has to be unique in one Table.';

        // Define.
        $this->addInfo('tableName', $tableName);
        $this->addInfo('columnName', $columnName);
        $this->addHint($hint);
    }
}
