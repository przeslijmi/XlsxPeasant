<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

/**
 * Font factory can work only on one or two params. Another combination is given.
 */
class FontFactoryFopException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param integer $paramCount Number of parameters given to factory.
     */
    public function __construct(int $paramCount)
    {

        // Lvd.
        $hint = 'FontFactory has been called with too many or wrong parameters.';

        // Define.
        $this->addInfo('paramCount', (string) $paramCount);
        $this->addHint($hint);
    }
}
