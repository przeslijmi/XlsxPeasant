<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ParamWrotypeException;
use Throwable;

/**
 * While setting defaults wront type of value has been used for particular setting.
 */
class WrotypeDefaultsException extends ParamWrotypeException
{

    /**
     * Constructor.
     *
     * @param string         $defaultsName Name of the parameter with error.
     * @param string         $typeExpected What type should be given.
     * @param string         $actualType   Actually given type.
     * @param Throwable|null $cause        Throwable that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(
        string $defaultsName,
        string $typeExpected,
        string $actualType,
        ?Throwable $cause = null
    ) {

        // Lvd.
        $hint  = 'Default `' . $defaultsName . '` is set with a `' . $actualType . '` value which is wrong. ';
        $hint .= 'Expected type is `' . $typeExpected . '`.';

        // Define.
        $this->addInfo('paramName', $defaultsName);
        $this->addInfo('typeExpected', $typeExpected);
        $this->addInfo('actualType', $actualType);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct($defaultsName, $typeExpected, $actualType, $cause);
        }
    }
}
