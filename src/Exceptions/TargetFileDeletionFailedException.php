<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Throwable;

/**
 * Generation ox XLSx file has failed.
 */
class TargetFileDeletionFailedException extends MethodFopException
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
        $hint  = 'Generation of XLSx failed because target file to generate to is unaccesible.';
        $hint .= 'Check permissions or maybe file is still in use?';

        // Define.
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('TargetFileDeletionFailedException', $cause);
        }
    }
}
