<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ParamWrosynException;

/**
 * Name of Column has wrong syntax.
 */
class ColumnNameWrosynException extends ParamWrosynException
{

    /**
     * Constructor.
     *
     * @param string         $name  Name of table that is wrong.
     * @param Exception|null $cause Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $name, ?Exception $cause = null)
    {

        // Lvd.
        $hint = 'Name of Column has to be max 255 characters long.';

        // Define.
        $this->setCodeName('ColumnNameWrosynException');
        $this->addInfo('paramName', 'columnName');
        $this->addInfo('actualValue', $name);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('columnName', $name, $cause);
        }
    }
}
