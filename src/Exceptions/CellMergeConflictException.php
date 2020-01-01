<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\XlsxPeasant\Helpers\Tools;
use Throwable;

/**
 * User is trying to merge over cells that are already used/defined.
 */
class CellMergeConflictException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string         $cellRef Refs of Cell.
     * @param Throwable|null $cause   Throwable that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $cellRef, ?Throwable $cause = null)
    {

        // Lvd.
        $hint   = 'Merging is trying to overwrite cell ' . $cellRef . ' which is forbidden. ';
        $hint  .= 'Delete used Cell or move merge.';
        $colRef = preg_replace('/([^A-Z])/', '', $cellRef);

        // Define.
        $this->addInfo('cellRef', $cellRef);
        $this->addInfo('colRef', $colRef);
        $this->addInfo('col', (string) Tools::convRefToNumber($colRef));
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('CellMergeConflictException', $cause);
        }
    }
}
