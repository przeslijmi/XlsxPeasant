<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items;

use Przeslijmi\XlsxPeasant\Exceptions\FillFactoryFopException;
use Przeslijmi\XlsxPeasant\Items;
use Przeslijmi\XlsxPeasant\Items\Color;

/**
 * Fill definition used in Style.
 */
class Fill
{

    /**
     * Color object - as color of the fill.
     *
     * @var Color
     */
    private $color;

    /**
     * Factory of Fill.
     *
     * @throws FillFactoryFopException When called with wrong parameters.
     * @return Fill
     */
    public static function factory() : Fill
    {

        // Lvd.
        $pm    = func_get_args();
        $count = count($pm);

        // Given 1 param.
        if ($count === 1) {

            // Given 1 param - Fill object itself.
            if (is_a($pm[0], self::class) === true) {
                return $pm[0];
            }

            // Given 1 param - assuming this is color given.
            return new Fill(Color::factory($pm[0]));
        }

        throw new FillFactoryFopException($count);
    }

    /**
     * Construct.
     *
     * @param Color $color Color to use in Fill.
     */
    public function __construct(Color $color)
    {

        $this->color = $color;
    }

    /**
     * Getter for Color.
     *
     * @return Color
     */
    public function getColor() : Color
    {

        return $this->color;
    }

    /**
     * Getter for signature.
     *
     * @return string
     */
    public function getSignature() : string
    {

        return 'fillColor:' . $this->getColor()->get();
    }
}
