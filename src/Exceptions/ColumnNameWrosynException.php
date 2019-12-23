<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ParamWrosynException;
use Throwable;

/**
 * Name of Column has wrong syntax.
 */
class ColumnNameWrosynException extends ParamWrosynException
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
        $hint = 'Name of Column has to be max 255 characters long.';

        // Define.
        $this->addInfo('paramName', 'columnName');
        $this->addInfo('actualValue', $name);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('columnName', $name, $cause);
        }
    }
}
