<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ParamOtoranException;
use Throwable;

/**
 * Fill factory can work only on one params. Another combination is given.
 */
class UnservedUnicodeException extends ParamOtoranException
{

    /**
     * Constructor.
     *
     * @param integer        $decUnicode Deciman integer - number of char in unicode.
     * @param Throwable|null $cause      Throwable that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(int $decUnicode, ?Throwable $cause = null)
    {

        // Lvd.
        $hint = 'Unicode above U+03FF are not served.';

        // Define.
        $this->addInfo('decUnicode', (string) $decUnicode);
        $this->addInfo('hint', $hint);

        // Set cause.
        if (is_null($cause) === false) {
            parent::__construct('decUnicode', '1 ... 1023', $decUnicode, $cause);
        }
    }
}
