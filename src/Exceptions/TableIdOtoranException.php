<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ParamOtoranException;

/**
 * Generation ox XLSX file has failed.
 */
class TableIdOtoranException extends ParamOtoranException
{

    /**
     * Constructor.
     *
     * @param integer $id Given wrong id.
     */
    public function __construct(int $id)
    {

        // Lvd.
        $hint = 'Proposed Table ID is wrong. Has to be >=1, `' . $id . '` given.';

        // Define.
        $this->addInfo('paramName', 'tableId');
        $this->addInfo('range', '>=1');
        $this->addInfo('actualValue', (string) $id);
        $this->addHint($hint);
    }
}
