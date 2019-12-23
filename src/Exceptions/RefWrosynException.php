<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ParamOtoranException;
use Throwable;

/**
 * Parameter's given value is out of set (enum - not out of range [i .... j]).
 */
class RefWrosynException extends ParamOtoranException
{

    /**
     * Constructor.
     *
     * @param string         $context  During what operation, what is the nature of the error.
     * @param string         $name     Percise name of the context (sheet name, table name, etc.).
     * @param integer        $givenRow Id of row.
     * @param integer        $givenCol Id of column.
     * @param Throwable|null $cause    Throwable that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(
        string $context,
        string $name,
        int $givenRow,
        int $givenCol,
        ?Throwable $cause = null
    ) {

        // Lvd.
        $range       = '(>=1, >=1)';
        $actualValue = '(' . $givenRow . ', ' . $givenCol . ')';

        // Define hint.
        $hint  = 'Given REF in Excel is wrong, has to be in (row, col) format with (>=1, >=1) rules. ';
        $hint .= 'While adding ' . $context . ' named `' . $name . '` REF (' . $givenRow . ', ' . $givenCol . ')';
        $hint .= ' is given.';

        // Define.
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
