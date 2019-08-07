<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ParamOtoranException;

/**
 * Parameter's given value is out of set (enum - not out of range [i .... j]).
 */
class RefWrosynException extends ParamOtoranException
{

    /**
     * Constructor.
     *
     * @param array          $possibleAligns Possible values that can be given.
     * @param string         $givenAlign     Actually given value.
     * @param Exception|null $cause          Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(
        string $what,
        string $name,
        int $givenRow,
        int $givenCol,
        ?Exception $cause = null
    ) {

        // Lvd.
        $range       = '(>=1, >=1)';
        $actualValue = '(' . $givenRow . ', ' . $givenCol . ')';

        // Define hint.
        $hint  = 'Given REF in Excel is wrong, has to be in (row, col) format with (>=1, >=1) rules. ';
        $hint .= 'While adding ' . $what . ' named `' . $name . '` REF (' . $givenRow . ', ' . $givenCol . ')';
        $hint .= ' is given.';

        // Define.
        $this->setCodeName('RefWrosynException');
        $this->addInfo('paramName', 'cellRef');
        $this->addInfo('range', $range);
        $this->addInfo('actualValue', $actualValue);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('cellRef', $range, $actualValue, $cause);
        }
    }
}
