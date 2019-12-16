<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items;

use Przeslijmi\XlsxPeasant\Exceptions\FontFactoryFopException;
use Przeslijmi\XlsxPeasant\Items\Font;

/**
 * Factory for Font definition used in Style.
 */
class FontFactory
{

    /**
     * Makes font and font variants.
     *
     * ## Usage example
     * ```
     * FontFactory::make('Arial');
     * FontFactory::make('Arial', '15 italic red');
     * ```
     *
     * @param null|Font|string $param1 Font object or font name in string.
     * @param null|string      $param2 Optional Font variant definition.
     *
     * @since  v1.0
     * @throws FontFactoryFopException When called with wrong parameters.
     * @return Font
     *
     * @phpcs:disable Zend.NamingConventions.ValidVariableName.ContainsNumbers
     * @phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
     */
    public static function make($param1, ?string $param2 = null) : Font
    {

        // Lvd.
        $pm    = func_get_args();
        $count = count($pm);

        // Given 1 param.
        if ($count === 1) {

            // Given 1 param - Font object itself.
            if (is_a($pm[0], 'Przeslijmi\XlsxPeasant\Items\Font') === true) {
                return $pm[0];
            }

            // Given 1 param - a string - assuming this is font name.
            if (is_string($pm[0]) === true) {

                // Define.
                $font = new Font();
                $font->setName($pm[0]);

                return $font;
            }
        }

        // Given 2 param.
        if ($count === 2) {
            return self::makeVariant($pm[0], $pm[1]);
        }

        throw new FontFactoryFopException($count);
    }

    /**
     * Makes font variants.
     *
     * ## Usage example
     * ```
     * FontFactory::make('Arial', '15 italic red');
     * FontFactory::make('Arial', '15');
     * FontFactory::make(null, '10 bold black');
     * ```
     *
     * @param null|Font|string $name    Font object or font name in string - or null if font is not to be changed.
     * @param null|string      $variant Optional Font variant definition.
     *
     * @since  v1.0
     * @return Font
     */
    public static function makeVariant(?string $name = null, string $variant) : Font
    {

        // Create Font.
        $font = new Font();

        // Add font name (if asked).
        if (empty($name) === false) {
            $font->setName($name);
        }

        // Look for emphasis.
        preg_match_all('/(\b(bold|italic|underline)\b)/', $variant, $foundPreg);
        $variant = preg_replace('/(\b(bold|italic|underline)\b)/', '', $variant);
        if (isset($foundPreg[0]) === true) {
            $foundPreg = array_flip($foundPreg[0]);

            if (isset($foundPreg['bold']) === true) {
                $font->setBold(true);
            }
            if (isset($foundPreg['italic']) === true) {
                $font->setItalic(true);
            }
            if (isset($foundPreg['underline']) === true) {
                $font->setUnderline(true);
            }
        }

        // Look for size.
        preg_match_all('/(\d)+/', $variant, $foundPreg);
        $variant = preg_replace('/(\d)+/', '', $variant);
        if (isset($foundPreg[0][0]) === true) {
            $font->setSize((int) $foundPreg[0][0]);
        }

        // Look for color.
        preg_match_all('/(\b([a-zA-Z]+)\b)/', $variant, $foundPreg);
        $variant = preg_replace('/(\d)+/', '', $variant);
        if (isset($foundPreg[0][0]) === true) {
            $font->setColor($foundPreg[0][0]);
        }

        return $font;
    }

    /**
     * Create Font as a merge (sum) of two Fonts: A + B.
     *
     * @param Font $fontA Base font.
     * @param Font $fontB Additional font.
     *
     * @since  v1.0
     * @return Font
     */
    public static function makeMerged(Font $fontA, Font $fontB) : Font
    {

        // Clone.
        $font = clone $fontA;

        // Define to A what was defined in B.
        if ($fontB->hasName() === true) {
            $font->setName($fontB->getName());
        }
        if ($fontB->hasSize() === true) {
            $font->setSize($fontB->getSize());
        }
        if ($fontB->hasColor() === true) {
            $font->setColor($fontB->getColor());
        }
        if ($fontB->hasBold() === true) {
            $font->setBold($fontB->isBold());
        }
        if ($fontB->hasItalic() === true) {
            $font->setItalic($fontB->isItalic());
        }
        if ($fontB->hasUnderline() === true) {
            $font->setUnderline($fontB->isUnderline());
        }

        return $font;
    }
}
