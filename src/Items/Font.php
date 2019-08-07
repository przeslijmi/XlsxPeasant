<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items;

use Przeslijmi\XlsxPeasant\Items\Color;
use Przeslijmi\XlsxPeasant\Items\FontFactory;

/**
 * Font definition used in Style.
 */
class Font
{

    /**
     * Name of font.
     *
     * @var string
     */
    private $name;

    /**
     * Size of font.
     *
     * @var integer
     */
    private $size;

    /**
     * Color of font.
     *
     * @var Color
     */
    private $color;

    /**
     * Is it bold.
     *
     * @var boolean
     */
    private $bold;

    /**
     * Is it italic.
     *
     * @var boolean
     */
    private $italic;

    /**
     * Is it underline.
     *
     * @var boolean
     */
    private $underline;

    /**
     * Factory of Font redirects to FontFactory class.
     *
     * @since  v1.0
     * @return Font
     */
    public static function factory() : Font
    {

        return FontFactory::make(...func_get_args());
    }

    /**
     * If font has a name.
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasName() : bool
    {

        return ! ( $this->name === null );
    }

    /**
     * Setter for name.
     *
     * @param string $name Name of font.
     *
     * @since  v1.0
     * @return self
     */
    public function setName(string $name) : self
    {

        $this->name = $name;

        return $this;
    }

    /**
     * Getter for name.
     *
     * @since  v1.0
     * @return null|string
     */
    public function getName() : ?string
    {

        return $this->name;
    }

    /**
     * If font has a size.
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasSize() : bool
    {

        return ! ( $this->size === null );
    }

    /**
     * Setter for size.
     *
     * @param integer $size Size of font.
     *
     * @since  v1.0
     * @return self
     */
    public function setSize(int $size) : self
    {

        $this->size = $size;

        return $this;
    }

    /**
     * Getter for size.
     *
     * @since  v1.0
     * @return null|integer
     */
    public function getSize() : ?int
    {

        return $this->size;
    }

    /**
     * If font has a color.
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasColor() : bool
    {

        return ! ( $this->color === null );
    }

    /**
     * Setter for Color.
     *
     * @since  v1.0
     * @return self
     */
    public function setColor() : self
    {

        $this->color = Color::factory(...func_get_args());

        return $this;
    }

    /**
     * Getter for Color.
     *
     * @since  v1.0
     * @return null|Color
     */
    public function getColor() : ?Color
    {

        return $this->color;
    }

    /**
     * If font has a bold definition (false or true, but it has).
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasBold() : bool
    {

        return ! ( $this->bold === null );
    }

    /**
     * Setter for bold.
     *
     * @param boolean $bold If Font is bold.
     *
     * @since  v1.0
     * @return self
     */
    public function setBold(bool $bold) : self
    {


        $this->bold = $bold;

        return $this;
    }

    /**
     * Getter for bold.
     *
     * @since  v1.0
     * @return null|boolean
     */
    public function isBold() : ?bool
    {

        return $this->bold;
    }

    /**
     * If font has a italic definition (false or true, but it has).
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasItalic() : bool
    {

        return ! ( $this->italic === null );
    }

    /**
     * Setter for italic.
     *
     * @param boolean $italic If Font is italic.
     *
     * @since  v1.0
     * @return self
     */
    public function setItalic(bool $italic) : self
    {

        $this->italic = $italic;

        return $this;
    }

    /**
     * Getter for italic.
     *
     * @since  v1.0
     * @return null|boolean
     */
    public function isItalic() : ?bool
    {

        return $this->italic;
    }

    /**
     * If font has a underline definition (false or true, but it has).
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasUnderline() : bool
    {

        return ! ( $this->underline === null );
    }

    /**
     * Setter for underline.
     *
     * @param boolean $underline If Font is underline.
     *
     * @since  v1.0
     * @return self
     */
    public function setUnderline(bool $underline) : self
    {

        $this->underline = $underline;

        return $this;
    }

    /**
     * Getter for underline.
     *
     * @since  v1.0
     * @return null|boolean
     */
    public function isUnderline() : ?bool
    {

        return $this->underline;
    }

    /**
     * Getter for signature.
     *
     * @since  v1.0
     * @return string
     */
    public function getSignature() : string
    {

        // Lvd.
        $result = '';

        // Fill up.
        if ($this->hasName() === true) {
            $result .= 'fontName:' . $this->getName();
        }
        if ($this->hasSize() === true) {
            $result .= 'fontSize:' . $this->getSize();
        }
        if ($this->hasColor() === true) {
            $result .= 'fontColor:' . $this->color->get();
        }
        if ($this->hasBold() === true) {
            $result .= 'bold:' . ( ( $this->bold === true ) ? 'true' : 'false' );
        }
        if ($this->hasItalic() === true) {
            $result .= 'italic:' . ( ( $this->italic === true ) ? 'true' : 'false' );
        }
        if ($this->hasUnderline() === true) {
            $result .= 'underline:' . ( ( $this->underline === true ) ? 'true' : 'false' );
        }

        return $result;
    }
}
