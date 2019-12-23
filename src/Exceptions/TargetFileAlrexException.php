<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\FileAlrexException;
use Throwable;

/**
 * Target file is taken but overwriting is forbidden.
 */
class TargetFileAlrexException extends FileAlrexException
{

    /**
     * Constructor.
     *
     * @param string         $fileUri Uri of file that can not be taken.
     * @param Throwable|null $cause   Throwable that caused problem.
     *
     * @since v1.0
     */
    public function __construct(string $fileUri, ?Throwable $cause = null)
    {

        // Lvd.
        $hint  = 'File URI to generate XLSx to is already taken.';
        $hint .= 'Change target URI or allow overwriting.';

        // Define.
        $this->addInfo('context', 'generatingXlsxFile');
        $this->addInfo('fileName', $fileUri);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('TargetFileAlrexExceptionOnGeneratingXlsxFile', $fileUri, $cause);
        }
    }
}
