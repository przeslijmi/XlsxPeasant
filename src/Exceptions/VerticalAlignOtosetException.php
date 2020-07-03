<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ParamOtosetException;

/**
 * Parameter's given value is out of set (enum - not out of range [i .... j]).
 */
class VerticalAlignOtosetException extends ParamOtosetException
{

    /**
     * Constructor.
     *
     * @param array  $possibleAligns Possible values that can be given.
     * @param string $givenAlign     Actually given value.
     */
    public function __construct(array $possibleAligns, string $givenAlign)
    {

        // Define.
        $this->addInfo('paramName', 'style vertical align');
        $this->addInfo('range', implode(', ', $possibleAligns));
        $this->addInfo('givenAlign', $givenAlign);
    }
}
