<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\FileAlrexException;
use Throwable;

/**
 * Target file URI is somehow corrupted.
 */
class TargetFileWrosynException extends FileAlrexException
{

    /**
     * Constructor.
     *
     * @param string         $fileUri Uri of file.
     * @param Throwable|null $cause   Throwable that caused problem.
     *
     * @since v1.0
     */
    public function __construct(string $fileUri, ?Throwable $cause = null)
    {

        // Lvd.
        $hint  = 'File URI to generate XLSx to is empty, or dir or has wrong syntax.';
        $hint .= 'Change target URI.';

        // Define.
        $this->addInfo('context', 'generatingXlsxFile');
        $this->addInfo('fileName', $fileUri);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('TargetFileWrosynExceptionOnGeneratingXlsxFile', $fileUri, $cause);
        }
    }
}
