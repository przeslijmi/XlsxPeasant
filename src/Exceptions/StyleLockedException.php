<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;

/**
 * Parameter's given value is out of set (enum - not out of range [i .... j]).
 */
class StyleLockedException extends ClassFopException
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
        $hint  = 'Trying to change definition of Style but Style is locked. ';
        $hint .= 'Release lock or don\'t try to make changes.';

        // Define.
        $this->setCodeName('StyleLockedException');
        $this->addInfo('context', 'StyleLock');
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('StyleLockedException', $cause);
        }
    }
}
