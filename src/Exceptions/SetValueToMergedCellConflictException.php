<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Throwable;

/**
 * User is trying to set value to Cell that is merged.
 */
class SetValueToMergedCellConflictException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string         $cellRef Refs of Cell.
     * @param Throwable|null $cause   Throwable that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $cellRef, ?Throwable $cause = null)
    {

        // Lvd.
        $hint = 'Adding value to Cell ' . $cellRef . ' is forbidden because this is merged Cell. ';

        // Define.
        $this->addInfo('cellRef', $cellRef);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('SetValueToMergedCellConflictException', $cause);
        }
    }
}
