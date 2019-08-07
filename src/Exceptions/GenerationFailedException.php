<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;

/**
 * Generation ox XLSx file has failed.
 */
class GenerationFailedException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param Exception|null $cause Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(?Exception $cause = null)
    {

        // Lvd.
        $hint = 'Generation of XLSx has somehow failed. See below.';

        // Define.
        $this->setCodeName('GenerationFailedException');
        $this->addInfo('context', 'StyleLock');
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('GenerationFailedException', $cause);
        }
    }
}
