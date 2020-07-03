<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

/**
 * User is trying to add Sheet with name that is already taken in this Book.
 */
class SheetNameAlrexException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string $name Name of Sheet that is duplicated.
     */
    public function __construct(string $name)
    {

        // Lvd.
        $hint  = 'Trying to add second Sheet with the same name `' . $name . '`. ';
        $hint .= 'Name of Sheets are unique in the entire XLSX.';

        // Define.
        $this->addInfo('name', $name);
        $this->addHint($hint);
    }
}
