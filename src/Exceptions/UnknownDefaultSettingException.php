<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ParamOtosetException;

/**
 * Generation ox XLSx file has failed.
 */
class UnknownDefaultSettingException extends ParamOtosetException
{

    /**
     * Constructor.
     *
     * @param string         $actualValue Actually given value.
     * @param array          $range       Possible values that can be given.
     * @param Exception|null $cause       Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $actualValue, array $range, ?Exception $cause = null)
    {

        // Lvd.
        $hint  = 'Trying to set or get default setting `' . $actualValue . '` which does not exists.';
        $hint .= ' Possible are: ' . implode(', ', $range) . '.';

        // Define.
        $this->setCodeName('UnknownDefaultSettingException');
        $this->addInfo('paramName', 'defaultsName');
        $this->addInfo('range', implode(', ', $range));
        $this->addInfo('actualValue', $actualValue);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('defaultsName', $range, $actualValue, $cause);
        }
    }
}
