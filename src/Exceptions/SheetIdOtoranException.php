<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ParamOtoranException;

/**
 * Sheet ID is out of range (below 1).
 */
class SheetIdOtoranException extends ParamOtoranException
{

    /**
     * Constructor.
     *
     * @param integer $id Given wrong id.
     *
     * @since v1.0
     */
    public function __construct(int $id)
    {

        // Lvd.
        $hint = 'Proposed Sheet ID is wrong. Has to be >=1, `' . $id . '` given.';

        // Define.
        $this->addInfo('paramName', 'tableId');
        $this->addInfo('range', '>=1');
        $this->addInfo('actualValue', (string) $id);
        $this->addHint($hint);
    }
}
