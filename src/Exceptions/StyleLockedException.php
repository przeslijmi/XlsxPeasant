<?php declare(strict_types=1);

namespace Przeslijmi\XlsxGenerator\Exceptions;

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

        $this->setCodeName('StyleLockedException');
        $this->addInfo('context', 'StyleLock');
        $this->addInfo('expl', 'Trying to change definition of Style but Style is locked. Release lock or don\'t try to make changes.');

        if (is_null($cause) === false) {
            parent::__construct('StyleLock', $cause);
        }
    }
}
