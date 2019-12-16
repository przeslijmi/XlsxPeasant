<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ValueWrotypeException;
use Przeslijmi\XlsxPeasant\Items\Cell;

/**
 * Cell value is in wrong type to perform operation.
 */
class CellValueWrotypeException extends ValueWrotypeException
{

    /**
     * Constructor.
     *
     * @param Cell $cell Call causing the problem.
     *
     * @since v1.0
     */
    public function __construct(Cell $cell)
    {

        // Lvd.
        $hint = 'You\'re trying to read numeric value from cell that has non-numeric value.';

        // Define.
        $this->addInfo('sheetName', $cell->getSheet()->getName());
        $this->addInfo('cellRef', $cell->getRef());
        $this->addInfo('hint', $hint);
    }
}
