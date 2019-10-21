<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\LoopOtoranException;

/**
 * Generator was looking for spare id - but it failed after 10000 tries.
 */
class LookingForSpareIdLoopOtoranException extends LoopOtoranException
{

    /**
     * Constructor.
     *
     * @since v1.0
     */
    public function __construct()
    {

        // Lvd.
        $hint = 'Generator was looking for spare id - but it failed after 10000 tries.';

        // Define.
        $this->addInfo('hint', $hint);
        $this->addInfo('maxRange', $maxRange);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('LookingForSpareIdLoopOtoranException', $cause);
        }
    }
}
