<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\DirDonoexException;
use Throwable;

/**
 * Target directory not exists - have to be created.
 */
class TargetDirectoryDonoexException extends DirDonoexException
{

    /**
     * Constructor.
     *
     * @param string         $directoryUri Uri of file that can not be taken.
     * @param string         $fullUri      Full uri of XLSX.
     * @param Throwable|null $cause        Throwable that caused problem.
     *
     * @since v1.0
     */
    public function __construct(string $directoryUri, string $fullUri, ?Throwable $cause = null)
    {

        // Lvd.
        $hint = 'File URI to generate XLSx has to be located in existing directory.';

        // Define.
        $this->addInfo('context', 'generatingXlsxFile');
        $this->addInfo('directoryUri', $directoryUri);
        $this->addInfo('fullUri', $fullUri);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct(self::class, $directoryUri, $cause);
        }
    }
}
