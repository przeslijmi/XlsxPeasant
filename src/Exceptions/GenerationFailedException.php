<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Throwable;

/**
 * Generation ox XLSx file has failed.
 */
class GenerationFailedException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param Throwable|null $cause Throwable that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(?Throwable $cause = null)
    {

        // Lvd.
        $hint = 'Generation of XLSx has somehow failed. See below.';

        // Define.
        $this->addInfo('context', 'GenerationOfXlsxFile');
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('GenerationFailedException', $cause);
        }
    }
}
