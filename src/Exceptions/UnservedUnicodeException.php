<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ParamOtoranException;

/**
 * Fill factory can work only on one params. Another combination is given.
 */
class UnservedUnicodeException extends ParamOtoranException
{

    /**
     * Constructor.
     *
     * @param integer $decUnicode Deciman integer - number of char in unicode.
     *
     * @since v1.0
     */
    public function __construct(int $decUnicode)
    {

        // Lvd.
        $hint = 'Unicode above U+03FF are not served.';

        // Define.
        $this->addInfo('decUnicode', (string) $decUnicode);
        $this->addHint($hint);
    }
}
