<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ParamOtosetException;

/**
 * Parameter's given value is out of set (enum - not out of range [i .... j]).
 */
class VerticalAlignOtosetException extends ParamOtosetException
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
    public function __construct(array $possibleAligns, string $givenAlign, ?Exception $cause = null)
    {

        // Define.
        $this->setCodeName('VerticalAlignOtosetException');
        $this->addInfo('paramName', 'style vertical align');
        $this->addInfo('range', implode(', ', $possibleAligns));
        $this->addInfo('givenAlign', $givenAlign);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('VerticalAlignOtosetException', $cause);
        }
    }
}
