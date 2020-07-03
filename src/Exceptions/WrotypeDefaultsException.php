<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ParamWrotypeException;

/**
 * While setting defaults wront type of value has been used for particular setting.
 */
class WrotypeDefaultsException extends ParamWrotypeException
{

    /**
     * Constructor.
     *
     * @param string $defaultsName Name of the parameter with error.
     * @param string $typeExpected What type should be given.
     * @param string $actualType   Actually given type.
     */
    public function __construct(
        string $defaultsName,
        string $typeExpected,
        string $actualType
    ) {

        // Lvd.
        $hint  = 'Default `' . $defaultsName . '` is set with a `' . $actualType . '` value which is wrong. ';
        $hint .= 'Expected type is `' . $typeExpected . '`.';

        // Define.
        $this->addInfo('paramName', $defaultsName);
        $this->addInfo('typeExpected', $typeExpected);
        $this->addInfo('actualType', $actualType);
        $this->addHint($hint);
    }
}
