<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

/**
 * Generation ox XLSx file has failed.
 */
class TargetFileDeletionFailedException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @since v1.0
     */
    public function __construct()
    {

        // Lvd.
        $hint  = 'Generation of XLSx failed because target file to generate to is unaccesible.';
        $hint .= 'Check permissions or maybe file is still in use?';

        // Define.
        $this->addInfo('hint', $hint);
    }
}
