<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ObjectDonoexException;
use Throwable;

/**
 * Can't change set of columns - there is data already added.
 */
class TableDonoexException extends ObjectDonoexException
{

    /**
     * Constructor.
     *
     * @param string         $name  Name of table that has data.
     * @param Throwable|null $cause Throwable that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $name, ?Throwable $cause = null)
    {

        // Lvd.
        $hint = 'Trying to get Table by name - but Table with this name does not exists.';

        // Define.
        $this->addInfo('tableName', $name);
        $this->addInfo('context', 'GetXlsxTableByName');
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('TableDonoexException', $cause);
        }
    }
}
