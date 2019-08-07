<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

/**
 * Creation of Table has somehow failed.
 */
class TableCreationFopException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string         $name  Name of table that is duplicated.
     * @param Exception|null $cause Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $name, ?Exception $cause = null)
    {

        // Lvd.
        $hint = 'Creation of Table `' . $name . '` failed. See below for details.';

        // Define.
        $this->setCodeName('TableCreationFopException');
        $this->addInfo('name', $name);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('TableCreationFopException', $cause);
        }
    }
}
