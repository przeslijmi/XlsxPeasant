<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ObjectDonoexException;

/**
 * Can't change set of columns - there is data already added.
 */
class TableDonoexException extends ObjectDonoexException
{

    /**
     * Constructor.
     *
     * @param string $name Name of table that has data.
     *
     * @since v1.0
     */
    public function __construct(string $name)
    {

        // Lvd.
        $hint = 'Trying to get Table by name - but Table with this name does not exists.';

        // Define.
        $this->addInfo('tableName', $name);
        $this->addInfo('context', 'GetXlsxTableByName');
        $this->addInfo('hint', $hint);
    }
}
