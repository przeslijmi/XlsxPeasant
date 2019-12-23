<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ClassDonoexException;
use Throwable;

/**
 * No ZipArchive class is present - unable to do the job.
 */
class NoZipArchiveException extends ClassDonoexException
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
        $hint = 'ZipArchive PHP class is needed to use this tool.';

        // Define.
        $this->addInfo('context', 'startingXlsxPeasant');
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('NoZipArchiveException', 'ZipArchive', $cause);
        }
    }
}
