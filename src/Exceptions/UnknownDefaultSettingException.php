<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ParamOtosetException;

/**
 * Generation ox XLSX file has failed.
 */
class UnknownDefaultSettingException extends ParamOtosetException
{

    /**
     * Constructor.
     *
     * @param string $actualValue Actually given value.
     * @param array  $range       Possible values that can be given.
     *
     * @since v1.0
     */
    public function __construct(string $actualValue, array $range)
    {

        // Lvd.
        $hint  = 'Trying to set or get default setting `' . $actualValue . '` which does not exists.';
        $hint .= ' Possible are: ' . implode(', ', $range) . '.';

        // Define.
        $this->addInfo('paramName', 'defaultsName');
        $this->addInfo('range', implode(', ', $range));
        $this->addInfo('actualValue', $actualValue);
        $this->addHint($hint);
    }
}
