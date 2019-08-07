<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

/**
 * Fill factory can work only on one params. Another combination is given.
 */
class FillFactoryFopException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param integer        $paramCount Number of parameters given to factory.
     * @param Exception|null $cause      Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(int $paramCount, ?Exception $cause = null)
    {

        // Lvd.
        $hint = 'FillFactory has been called with too many or wrong parameters.';

        // Define.
        $this->setCodeName('FillFactoryFopException');
        $this->addInfo('paramCount', $paramCount);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('FillFactoryFopException', $cause);
        }
    }
}
