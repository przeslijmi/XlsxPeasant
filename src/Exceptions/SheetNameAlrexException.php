<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

/**
 * User is trying to add Sheet with name that is already taken in this Book.
 */
class SheetNameAlrexException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string         $name  Name of Sheet that is duplicated.
     * @param Exception|null $cause Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $name, ?Exception $cause = null)
    {

        // Lvd.
        $hint  = 'Trying to add second Sheet with the same name `' . $name . '`. ';
        $hint .= 'Name of Sheets are unique in the entire XLSX.';

        // Define.
        $this->setCodeName('SheetNameAlrexException');
        $this->addInfo('name', $name);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('SheetNameAlrexException', $cause);
        }
    }
}
