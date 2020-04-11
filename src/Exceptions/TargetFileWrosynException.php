<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\FileAlrexException;

/**
 * Target file URI is somehow corrupted.
 */
class TargetFileWrosynException extends FileAlrexException
{

    /**
     * Constructor.
     *
     * @param string $fileUri Uri of file.
     *
     * @since v1.0
     */
    public function __construct(string $fileUri)
    {

        // Lvd.
        $hint  = 'File URI to generate XLSX to is empty, or dir or has wrong syntax.';
        $hint .= 'Change target URI.';

        // Define.
        $this->addInfo('context', 'generatingXlsxFile');
        $this->addInfo('fileName', $fileUri);
        $this->addInfo('hint', $hint);
    }
}
