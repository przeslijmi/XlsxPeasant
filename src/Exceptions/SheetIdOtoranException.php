<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ParamOtoranException;

/**
 * Sheet ID is out of range (below 1).
 */
class SheetIdOtoranException extends ParamOtoranException
{

    /**
     * Constructor.
     *
     * @param integer        $id    Given wrong id.
     * @param Exception|null $cause Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(int $id, ?Exception $cause = null)
    {

        // Lvd.
        $hint = 'Proposed Sheet ID is wrong. Has to be >=1, `' . $id . '` given.';

        // Define.
        $this->setCodeName('SheetIdOtoranException');
        $this->addInfo('paramName', 'tableId');
        $this->addInfo('range', '>=1');
        $this->addInfo('actualValue', $id);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('tableId', '>=1', $id, $cause);
        }
    }
}