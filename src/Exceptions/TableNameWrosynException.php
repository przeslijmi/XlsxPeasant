<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ParamWrosynException;
use Throwable;

/**
 * Generation ox XLSX file has failed.
 */
class TableNameWrosynException extends ParamWrosynException
{

    /**
     * Constructor.
     *
     * @param string         $name  Name of table that is wrong.
     * @param Throwable|null $cause Throwable that caused the problem.
     */
    public function __construct(string $name, ?Throwable $cause = null)
    {

        // Lvd.
        $hint  = 'Proposed Table name is wrong. Only numbers, letters, underscores and periods are allowed. ';
        $hint .= 'First character can not be number nor period. Name can not be reference to cell ';
        $hint .= '(eg R1C1, A1:B7, A$200, etc.). Name has to be max 255 characters long.';

        // Define.
        $this->addInfo('paramName', 'tableName');
        $this->addInfo('actualValue', $name);
        $this->addHint($hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('tableName', $name, $cause);
        }
    }
}
