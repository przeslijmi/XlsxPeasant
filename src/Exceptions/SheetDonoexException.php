<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ObjectDonoexException;
use Throwable;

/**
 * Can't find sheet by given ID or name.
 */
class SheetDonoexException extends ObjectDonoexException
{

    /**
     * Constructor.
     *
     * @param integer|string $idOrName Id or name of Sheet.
     * @param Throwable|null $cause    Throwable that caused the problem.
     *
     * @since v1.0
     */
    public function __construct($idOrName, ?Throwable $cause = null)
    {

        // Lvd.
        if (is_int($idOrName) === true) {
            $hint = 'Trying to get Sheet by ID - but Sheet with this ID does not exists.';
        } else {
            $hint = 'Trying to get Sheet by Name - but Sheet with this Name does not exists.';
        }

        // Define.
        $this->addInfo('sheetIdOrName', (string) $idOrName);
        $this->addInfo('context', 'GetXlsxSheet');
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('SheetDonoexException', $cause);
        }
    }
}
