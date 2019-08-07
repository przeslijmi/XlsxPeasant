<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

/**
 * User is trying to merge over cells that are already used/defined.
 */
class CellMergeConflictException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string         $cellRef Refs of Cell.
     * @param Exception|null $cause   Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $cellRef, ?Exception $cause = null)
    {

        // Lvd.
        $hint  = 'Merging is trying to overwrite cell ' . $cellRef . ' which is forbidden. ';
        $hint .= 'Delete used Cell or move merge.';

        // Define.
        $this->setCodeName('CellMergeConflictException');
        $this->addInfo('cellRef', $cellRef);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('CellMergeConflictException', $cause);
        }
    }
}
