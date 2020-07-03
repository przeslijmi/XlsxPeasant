<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

/**
 * User is trying to set value to Cell that is merged.
 */
class SetValueToMergedCellConflictException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string $cellRef Refs of Cell.
     */
    public function __construct(string $cellRef)
    {

        // Lvd.
        $hint = 'Adding value to Cell ' . $cellRef . ' is forbidden because this is merged Cell. ';

        // Define.
        $this->addInfo('cellRef', $cellRef);
        $this->addHint($hint);
    }
}
