<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\FileAlrexException;

/**
 * Target file URI is somehow corrupted.
 */
class TargetFileWrosynException extends FileAlrexException
{

    /**
     * Constructor.
     *
     * @param string         $fileUri Uri of file.
     * @param Exception|null $cause   Exception that caused problem.
     *
     * @since v1.0
     */
    public function __construct(string $fileUri, ?Exception $cause = null)
    {

        // Lvd.
        $hint  = 'File URI to generate XLSx to is empty, or dir or has wrong syntax.';
        $hint .= 'Change target URI.';

        // Define.
        $this->setCodeName('TargetFileWrosynException');
        $this->addInfo('context', 'generatingXlsxFile');
        $this->addInfo('fileName', $fileUri);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('TargetFileWrosynExceptionOnGeneratingXlsxFile', $fileUri, $cause);
        }
    }
}
