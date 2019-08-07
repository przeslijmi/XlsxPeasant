<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ClassDonoexException;

/**
 * No ZipArchive class is present - unable to do the job.
 */
class NoZipArchiveException extends ClassDonoexException
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
        $hint = 'ZipArchive PHP class is needed to use this tool.';

        // Define.
        $this->setCodeName('NoZipArchiveException');
        $this->addInfo('context', 'startingXlsxPeasant');
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('NoZipArchiveException', 'ZipArchive', $cause);
        }
    }
}
