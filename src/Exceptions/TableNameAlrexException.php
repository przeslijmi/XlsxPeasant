<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

/**
 * Generation ox XLSx file has failed.
 */
class TableNameAlrexException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string         $name  Name of table that is duplicated.
     * @param Exception|null $cause Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $name, ?Exception $cause = null)
    {

        // Lvd.
        $hint  = 'Trying to add second table with the same name `' . $name . '`. ';
        $hint .= 'Name of tables are unique in the entire XLSX.';

        // Define.
        $this->setCodeName('TableNameAlrexException');
        $this->addInfo('name', $name);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('TableNameAlrexException', $cause);
        }
    }
}
