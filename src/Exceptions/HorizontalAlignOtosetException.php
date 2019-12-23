<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ParamOtosetException;
use Throwable;

/**
 * Parameter's given value is out of set (enum - not out of range [i .... j]).
 */
class HorizontalAlignOtosetException extends ParamOtosetException
{

    /**
     * Constructor.
     *
     * @param array          $possibleAligns Possible values that can be given.
     * @param string         $givenAlign     Actually given value.
     * @param Throwable|null $cause          Throwable that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(array $possibleAligns, string $givenAlign, ?Throwable $cause = null)
    {

        // Define.
        $this->addInfo('paramName', 'style horizontal align');
        $this->addInfo('range', implode(', ', $possibleAligns));
        $this->addInfo('givenAlign', $givenAlign);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('HorizontalAlignOtosetException', $cause);
        }
    }
}
