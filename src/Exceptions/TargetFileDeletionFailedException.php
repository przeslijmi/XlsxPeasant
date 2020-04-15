<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

/**
 * Generation ox XLSX file has failed.
 */
class TargetFileDeletionFailedException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string $fileUri Uri of file which created problem.
     */
    public function __construct(string $fileUri)
    {

        // Lvd.
        $hint  = 'Generation of XLSX failed because target file to generate to is unaccesible. ';
        $hint .= 'Check permissions or maybe file is still in use?';

        // Define.
        $this->addHint($hint);
        $this->addInfo('fileUri', $fileUri);
    }
}
