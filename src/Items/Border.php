<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items;

use Przeslijmi\Sivalidator\RegEx;
use Przeslijmi\XlsxPeasant\Items;
use Przeslijmi\XlsxPeasant\Items\Border;
use Przeslijmi\XlsxPeasant\Xlsx;

/**
 * Border definition used in Style objects.
 */
class Border
{

    /**
     * Glossary of standard thickness options.
     *
     * @var array
     */
    public const THICKNESS = [
        'thin'             => 'thin',
        'medium'           => 'medium',
        'thick'            => 'thick',
        'dotted'           => 'dotted',
        'hair'             => 'hair',
        'dashed'           => 'dashed',
        'mediumDashed'     => 'mediumDashed',
        'dashDot'          => 'dashDot',
        'mediumDashDot'    => 'mediumDashDot',
        'dashDotDot'       => 'dashDotDot',
        'mediumDashDotDot' => 'mediumDashDotDot',
    ];

    /**
     * Glossary of standard thickness options.
     *
     * @var array
     */
    public const SIDE = [
        'top'      => 'top',
        'right'    => 'right',
        'bottom'   => 'bottom',
        'left'     => 'left',
        'diagonal' => 'diagonal',
    ];

    /**
     * On which side this border can be used.
     *
     * @var string
     */
    private $side

    /**
     * Border of border.
     *
     * @var Border
     */
    private $color;

    /**
     * Factory of Border object.
     *
     * ## Usage example
     * ```
     * $border = Border::factory('top', 'white');
     * $border = Border::factory('bottom', 'white', 'dashed');
     * ```
     *
     * @param string             $side      On which side.
     * @param string|array|Color $color     Which color.
     * @param string             $thickness Which thickness.
     *
     * @since  v1.0
     * @return Border
     */
    public static function factory(string $side, $color, string $thickness = 'thin') : Border
    {

        return new Border($side, Color::factory($color), $thickness);
    }

    /**
     * Constructor.
     *
     * @param string             $side      On which side.
     * @param string|array|Color $color     Which color.
     * @param string             $thickness Which thickness.
     *
     * @since  v1.0
     */
    public function __construct(string $side, $color, string $thickness = 'thin') : self
    {

        // Set.
        $this->side      = $side;
        $this->color     = Color::factory($color);
        $this->thickness = $thickness;

        return $this;
    }
}
