<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Throwable;

/**
 * Creation of Table has somehow failed.
 */
class TableCreationFopException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string         $name  Name of table that is duplicated.
     * @param Throwable|null $cause Throwable that caused the problem.
     */
    public function __construct(string $name, ?Throwable $cause = null)
    {

        // Lvd.
        $hint = 'Creation of Table `' . $name . '` failed. See below for details.';

        // Define.
        $this->addInfo('name', $name);
        $this->addHint($hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('TableCreationFopException', $cause);
        }
    }
}
