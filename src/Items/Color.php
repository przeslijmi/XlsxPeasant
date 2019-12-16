<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ParamOtoranException;
use Przeslijmi\Sexceptions\Exceptions\ParamWrosynException;
use Przeslijmi\Sivalidator\RegEx;
use Przeslijmi\XlsxPeasant\Exceptions\ColorFactoryFopException;
use Przeslijmi\XlsxPeasant\Exceptions\ColorNameOtosetException;
use Przeslijmi\XlsxPeasant\Items;
use Przeslijmi\XlsxPeasant\Xlsx;

/**
 * Color definition used in Font and Fill objects.
 */
class Color
{

    /**
     * Dictionary of standard colors.
     *
     * @var array
     */
    public const DICTIONARY = [
        'ALICEBLUE'            => 'F0F8FF',
        'ANTIQUEWHITE'         => 'FAEBD7',
        'AQUA'                 => '00FFFF',
        'AQUAMARINE'           => '7FFFD4',
        'AZURE'                => 'F0FFFF',
        'BEIGE'                => 'F5F5DC',
        'BISQUE'               => 'FFE4C4',
        'BLACK'                => '000000',
        'BLANCHEDALMOND'       => 'FFEBCD',
        'BLUE'                 => '0000FF',
        'BLUEVIOLET'           => '8A2BE2',
        'BROWN'                => 'A52A2A',
        'BURLYWOOD'            => 'DEB887',
        'CADETBLUE'            => '5F9EA0',
        'CHARTREUSE'           => '7FFF00',
        'CHOCOLATE'            => 'D2691E',
        'CORAL'                => 'FF7F50',
        'CORNFLOWER'           => '6495ED',
        'CORNSILK'             => 'FFF8DC',
        'CRIMSON'              => 'DC143C',
        'CYAN'                 => '00FFFF',
        'DARKBLUE'             => '00008B',
        'DARKCYAN'             => '008B8B',
        'DARKGOLDENROD'        => 'B8860B',
        'DARKGRAY'             => 'A9A9A9',
        'DARKGREEN'            => '006400',
        'DARKKHAKI'            => 'BDB76B',
        'DARKMAGENTA'          => '8B008B',
        'DARKOLIVEGREEN'       => '556B2F',
        'DARKORANGE'           => 'FF8C00',
        'DARKORCHID'           => '9932CC',
        'DARKRED'              => '8B0000',
        'DARKSALMON'           => 'E9967A',
        'DARKSEAGREEN'         => '8FBC8B',
        'DARKSLATEBLUE'        => '483D8B',
        'DARKSLATEGRAY'        => '2F4F4F',
        'DARKTURQUOISE'        => '00CED1',
        'DARKVIOLET'           => '9400D3',
        'DEEPPINK'             => 'FF1493',
        'DEEPSKYBLUE'          => '00BFFF',
        'DIMGRAY'              => '696969',
        'DODGERBLUE'           => '1E90FF',
        'FIREBRICK'            => 'B22222',
        'FLORALWHITE'          => 'FFFAF0',
        'FORESTGREEN'          => '228B22',
        'FUCHSIA'              => 'FF00FF',
        'GAINSBORO'            => 'DCDCDC',
        'GHOSTWHITE'           => 'F8F8FF',
        'GOLD'                 => 'FFD700',
        'GOLDENROD'            => 'DAA520',
        'GRAY'                 => '808080',
        'GREEN'                => '008000',
        'GREENYELLOW'          => 'ADFF2F',
        'HONEYDEW'             => 'F0FFF0',
        'HOTPINK'              => 'FF69B4',
        'INDIANRED'            => 'CD5C5C',
        'INDIGO'               => '4B0082',
        'IVORY'                => 'FFFFF0',
        'KHAKI'                => 'F0E68C',
        'LAVENDER'             => 'E6E6FA',
        'LAVENDERBLUSH'        => 'FFF0F5',
        'LAWNGREEN'            => '7CFC00',
        'LEMONCHIFFON'         => 'FFFACD',
        'LIGHTBLUE'            => 'ADD8E6',
        'LIGHTCORAL'           => 'F08080',
        'LIGHTCYAN'            => 'E0FFFF',
        'LIGHTGOLDENRODYELLOW' => 'FAFAD2',
        'LIGHTGREEN'           => '90EE90',
        'LIGHTGRAY'            => 'D3D3D3',
        'LIGHTPINK'            => 'FFB6C1',
        'LIGHTSALMON'          => 'FFA07A',
        'LIGHTSEAGREEN'        => '20B2AA',
        'LIGHTSKYBLUE'         => '87CEFA',
        'LIGHTSLATEGRAY'       => '778899',
        'LIGHTSTEELBLUE'       => 'B0C4DE',
        'LIGHTYELLOW'          => 'FFFFE0',
        'LIME'                 => '00FF00',
        'LIMEGREEN'            => '32CD32',
        'LINEN'                => 'FAF0E6',
        'MAGENTA'              => 'FF00FF',
        'MAROON'               => '800000',
        'MEDIUMAQUAMARINE'     => '66CDAA',
        'MEDIUMBLUE'           => '0000CD',
        'MEDIUMORCHID'         => 'BA55D3',
        'MEDIUMPURPLE'         => '9370DB',
        'MEDIUMSEAGREEN'       => '3CB371',
        'MEDIUMSLATEBLUE'      => '7B68EE',
        'MEDIUMSPRINGGREEN'    => '00FA9A',
        'MEDIUMTURQUOISE'      => '48D1CC',
        'MEDIUMVIOLETRED'      => 'C71585',
        'MIDNIGHTBLUE'         => '191970',
        'MINTCREAM'            => 'F5FFFA',
        'MISTYROSE'            => 'FFE4E1',
        'MOCCASIN'             => 'FFE4B5',
        'NAVAJOWHITE'          => 'FFDEAD',
        'NAVY'                 => '000080',
        'OLDLACE'              => 'FDF5E6',
        'OLIVE'                => '808000',
        'OLIVEDRAB'            => '6B8E23',
        'ORANGE'               => 'FFA500',
        'ORANGERED'            => 'FF4500',
        'ORCHID'               => 'DA70D6',
        'PALEGOLDENROD'        => 'EEE8AA',
        'PALEGREEN'            => '98FB98',
        'PALETURQUOISE'        => 'AFEEEE',
        'PALEVIOLETRED'        => 'DB7093',
        'PAPAYAWHIP'           => 'FFEFD5',
        'PEACHPUFF'            => 'FFDAB9',
        'PERU'                 => 'CD853F',
        'PINK'                 => 'FFC0CB',
        'PLUM'                 => 'DDA0DD',
        'POWDERBLUE'           => 'B0E0E6',
        'PURPLE'               => '800080',
        'RED'                  => 'FF0000',
        'ROSYBROWN'            => 'BC8F8F',
        'ROYALBLUE'            => '4169E1',
        'SADDLEBROWN'          => '8B4513',
        'SALMON'               => 'FA8072',
        'SANDYBROWN'           => 'F4A460',
        'SEAGREEN'             => '2E8B57',
        'SEASHELL'             => 'FFF5EE',
        'SIENNA'               => 'A0522D',
        'SILVER'               => 'C0C0C0',
        'SKYBLUE'              => '87CEEB',
        'SLATEBLUE'            => '6A5ACD',
        'SLATEGRAY'            => '708090',
        'SNOW'                 => 'FFFAFA',
        'SPRINGGREEN'          => '00FF7F',
        'STEELBLUE'            => '4682B4',
        'TAN'                  => 'D2B48C',
        'TEAL'                 => '008080',
        'THISTLE'              => 'D8BFD8',
        'TOMATO'               => 'FF6347',
        'TURQUOISE'            => '40E0D0',
        'VIOLET'               => 'EE82EE',
        'WHEAT'                => 'F5DEB3',
        'WHITE'                => 'FFFFFF',
        'WHITESMOKE'           => 'F5F5F5',
        'YELLOW'               => 'FFFF00',
        'YELLOWGREEN'          => '9ACD32',
    ];

    /**
     * Default color - white.
     *
     * @var string[]
     */
    private $color = [ 'FF', 'FF', 'FF' ];

    /**
     * Factory of Color object.
     *
     * ## Usage example
     * ```
     * $color = Color::factory($color);
     * $color = Color::factory(0, 15, 250);
     * $color = Color::factory('FFAB34');
     * $color = Color::factory('white');
     * ```
     *
     * @since  v1.0
     * @throws ColorNameOtosetException When color name donoex in dictionary.
     * @throws ColorFactoryFopException When called with wrong parameters.
     * @return Color
     */
    public static function factory() : Color
    {

        // Lvd.
        $pm    = func_get_args();
        $count = count($pm);

        // Given 1 param.
        if ($count === 1) {

            // Given 1 param - Color object itself.
            if (is_a($pm[0], self::class) === true) {
                return $pm[0];
            }

            // Given 1 param - uppercased string with 6 chars (hexadecimal value).
            if (is_string($pm[0]) === true && RegEx::ifMatches($pm[0], '/^([0-9A-F]){6}$/', false) === true) {
                return ( new Color() )->set(...$pm);
            }

            // Given 1 param - other string.
            if (is_string($pm[0]) === true) {

                // Lvd.
                $colorName = strtoupper($pm[0]);

                // Throw.
                if (isset(self::DICTIONARY[$colorName]) === false) {
                    throw new ColorNameOtosetException($colorName);
                }

                // Lvd.
                $color = self::DICTIONARY[$colorName];

                return ( new Color() )->set($color);
            }
        }//end if

        // Given 3 params.
        if ($count === 3) {

            // Given 3 params and all are integers.
            if (is_int($pm[0]) === true && is_int($pm[1]) === true && is_int($pm[2]) === true) {
                return ( new Color() )->setRgb(...$pm);
            }
        }

        throw new ColorFactoryFopException($count);
    }

    /**
     * Sets hexadecimal value of RGB sent as string.
     *
     * @param string $rgb Hexadecimal value of RGB, eg. AA00ED.
     *
     * @since  v1.0
     * @throws ParamWrosynException When given RGB hex is wrong.
     * @return self
     */
    public function set(string $rgb) : self
    {

        // Test.
        try {
            RegEx::ifMatches($rgb, '/^([0-9A-F]){6}$/');
        } catch (Exception $exc) {
            throw (new ParamWrosynException('color_set_rgb', $rgb))
                ->addHint('Given color is not proper RGB hexadecimal value, eg. AA00ED.');
        }

        // Set.
        $this->color = [
            substr($rgb, 0, 2),
            substr($rgb, 2, 2),
            substr($rgb, 4, 2)
        ];

        return $this;
    }

    /**
     * Sets RGB sent as three integer - one for every channel.
     *
     * @param integer $red   Value for red channel.
     * @param integer $green Value for green channel.
     * @param integer $blue  Value for blue channel.
     *
     * @since  v1.0
     * @throws ParamOtoranException When red, blue or green parameters is out of range.
     * @return self
     */
    public function setRgb(int $red, int $green = 0, int $blue = 0) : self
    {

        // Test.
        if ($red < 0 || $red > 255) {
            throw new ParamOtoranException('color_set_red', '0 ... 255', (string) $red);
        } elseif ($green < 0 || $green > 255) {
            throw new ParamOtoranException('color_set_green', '0 ... 255', (string) $green);
        } elseif ($blue < 0 || $blue > 255) {
            throw new ParamOtoranException('color_set_blue', '0 ... 255', (string) $blue);
        }

        // Set.
        $this->color = [
            str_pad(dechex($red), 2, '0', STR_PAD_LEFT),
            str_pad(dechex($green), 2, '0', STR_PAD_LEFT),
            str_pad(dechex($blue), 2, '0', STR_PAD_LEFT)
        ];

        return $this;
    }

    /**
     * Return Color in XLSX value (preceded with FF).
     *
     * @since  v1.0
     * @return string
     */
    public function get() : string
    {

        return 'FF' . implode('', $this->color);
    }
}
