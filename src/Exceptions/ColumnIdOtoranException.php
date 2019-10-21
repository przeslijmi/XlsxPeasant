<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ParamOtoranException;

/**
 * Column ID is out of range (below 1).
 */
class ColumnIdOtoranException extends ParamOtoranException
{

    /**
     * Constructor.
     *
     * @param string         $tableName Name of table in which Columns are present.
     * @param integer        $id        Given wrong id.
     * @param Exception|null $cause     Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $tableName, int $id, ?Exception $cause = null)
    {

        // Lvd.
        $hint  = 'Proposed Column ID in table ' . $tableName . ' is wrong. ';
        $hint .= 'Has to be >=1, `' . $id . '` given.';

        // Define.
        $this->setCodeName('ColumnIdOtoranException');
        $this->addInfo('paramName', 'columnId');
        $this->addInfo('range', '>=1');
        $this->addInfo('actualValue', (string) $id);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('columnId', '>=1', $id, $cause);
        }
    }
}
