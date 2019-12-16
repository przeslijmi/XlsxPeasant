<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ValueOtosetException;
use Przeslijmi\XlsxPeasant\Items\Color;

/**
 * Color name is unknown (out of set).
 */
class ColorNameOtosetException extends ValueOtosetException
{

    /**
     * Constructor.
     *
     * @param string $colorName Name of unknown color.
     *
     * @since v1.0
     */
    public function __construct(string $colorName)
    {

        // Lvd.
        $hint = 'ColorFactory has been called with unknown color name.';

        // Define.
        $this->addInfo('name', 'colorName');
        $this->addInfo('actualValue', $colorName);
        $this->addInfo('range', implode(', ', Color::DICTIONARY));
        $this->addInfo('hint', $hint);
    }
}
