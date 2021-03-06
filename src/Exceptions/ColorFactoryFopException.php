<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

/**
 * Color factory can work only on one params. Another combination is given.
 */
class ColorFactoryFopException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param integer $paramCount Number of parameters given to factory.
     */
    public function __construct(int $paramCount)
    {

        // Lvd.
        $hint = 'ColorFactory has been called with too many or wrong parameters.';

        // Define.
        $this->addInfo('paramCount', (string) $paramCount);
        $this->addHint($hint);
    }
}
