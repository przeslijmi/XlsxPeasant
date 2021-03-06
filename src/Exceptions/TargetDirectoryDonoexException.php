<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\DirDonoexException;

/**
 * Target directory not exists - have to be created.
 */
class TargetDirectoryDonoexException extends DirDonoexException
{

    /**
     * Constructor.
     *
     * @param string $directoryUri Uri of file that can not be taken.
     * @param string $fullUri      Full uri of XLSX.
     */
    public function __construct(string $directoryUri, string $fullUri)
    {

        // Lvd.
        $hint = 'File URI to generate XLSX has to be located in existing directory.';

        // Define.
        $this->addInfo('context', 'generatingXlsxFile');
        $this->addInfo('directoryUri', $directoryUri);
        $this->addInfo('fullUri', $fullUri);
        $this->addHint($hint);
    }
}
