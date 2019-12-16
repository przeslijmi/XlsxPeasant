<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\LoopOtoranException;

/**
 * Generator was looking for spare id - but it failed after 10000 tries.
 */
class LookingForSpareIdLoopOtoranException extends LoopOtoranException
{

    /**
     * Constructor.
     *
     * @param integer $maxRange What was the maxiumum loop range reached.
     *
     * @since v1.0
     */
    public function __construct(int $maxRange)
    {

        // Lvd.
        $hint = 'Generator was looking for spare id - but it failed after ' . $maxRange . ' tries.';

        // Define.
        $this->addInfo('hint', $hint);
        $this->addInfo('maxRange', (string) $maxRange);
    }
}
