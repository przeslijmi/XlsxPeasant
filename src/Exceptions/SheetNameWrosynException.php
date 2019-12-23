<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ParamWrosynException;
use Throwable;

/**
 * Name of Sheet has wrong syntax.
 */
class SheetNameWrosynException extends ParamWrosynException
{

    /**
     * Constructor.
     *
     * @param string         $name  Name of table that is wrong.
     * @param Throwable|null $cause Throwable that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $name, ?Throwable $cause = null)
    {

        // Lvd.
        $hint  = 'Proposed Sheet name is wrong. Only numbers, letters, underscores and periods are allowed. ';
        $hint .= 'First character can not be number nor period. Name can not be reference to cell ';
        $hint .= '(eg R1C1, A1:B7, A$200, etc.). Name has to be max 255 characters long.';

        // Define.
        $this->addInfo('paramName', 'tableName');
        $this->addInfo('actualValue', $name);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('tableName', $name, $cause);
        }
    }
}
