<?php declare(strict_types=1);

namespace Przeslijmi\XlsxGenerator\Items;

use Przeslijmi\XlsxGenerator\Exceptions\HorizontalAlignOtosetException;
use Przeslijmi\XlsxGenerator\Exceptions\StyleLockedException;
use Przeslijmi\XlsxGenerator\Exceptions\VerticalAlignOtosetException;
use Przeslijmi\XlsxGenerator\Items;
use Przeslijmi\XlsxGenerator\Items\Cell;
use Przeslijmi\XlsxGenerator\Items\Color;
use Przeslijmi\XlsxGenerator\Items\Fill;
use Przeslijmi\XlsxGenerator\Items\Font;

/**
 * One Style can be used by one or more Cell objects.
 */
class Style extends Items
{

    /**
     * Id of style (defined later on creation of XLSX file).
     *
     * @var integer
     */
    private $id;

    /**
     * Fill of Cell.
     *
     * @var Fill
     */
    private $fill;

    /**
     * Font of Cell.
     *
     * @var Font
     */
    private $font;

    /**
     * Horizontal align in style (left, center, right).
     *
     * @var string
     */
    private $hAlign;

    /**
     * Verical align in style (top, center, bottom).
     *
     * @var string
     */
    private $vAlign;

    /**
     * If Style is locked (locked Style can't be changed).
     *
     * @var boolean
     */
    private $lock = false;

    /**
     * Getter for style ID of this Cell.
     *
     * @since  v1.0
     * @return ?integer
     */
    public function getId() : int
    {

        // If unknown - register Style in Styles.
        if ($this->id === null) {

            // It will cause filling $this->id.
            $this->xlsx->getStyles()->registerStyle($this);
        }

        return $this->id;
    }

    /**
     * Setter for id of style (used by XML files).
     *
     * @param integer $id Created id.
     *
     * @since  v1.0
     * @return self
     */
    public function setId(int $id) : self
    {

        $this->id = $id;

        return $this;
    }

    /**
     * Checks if style has defined Fill.
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasFill() : bool
    {

        return ! ( $this->fill === null );
    }

    /**
     * Setter for Fill of this Cell.
     *
     * @since  v1.0
     * @return self
     */
    public function setFill() : self
    {

        // Save fill.
        $this->fill = Fill::factory(...func_get_args());

        return $this;
    }

    /**
     * Getter for Fill of this Cell.
     *
     * @since  v1.0
     * @return ?Fill
     */
    public function getFill() : ?Fill
    {

        return $this->fill;
    }

    /**
     * Checks if style has defined Font.
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasFont() : bool
    {

        return ! ( $this->font === null );
    }

    /**
     * Setter for Font of this Cell.
     *
     * @param null|Font $font Font to use.
     *
     * @since  v1.0
     * @return self
     */
    public function setFont(?Font $font = null) : self
    {

        // Creates Font object (if needed).
        if ($font === null) {
            $font = new Font($this->xlsx);
        }

        // Saves font.
        $this->font = $font;

        return $this;
    }

    /**
     * Getter for Font of this Cell.
     *
     * @since  v1.0
     * @return ?Font
     */
    public function getFont() : ?Font
    {

        return $this->font;
    }

    /**
     * Setter for font name of this Cell.
     *
     * @param string $name Name of font.
     *
     * @since  v1.0
     * @return self
     */
    public function setFontName(string $name) : self
    {

        // Create font if there is no present.
        if ($this->font === null) {
            $this->setFont();
        }

        // Set its name.
        $this->font->setName($name);

        return $this;
    }

    /**
     * Setter for font size of this Cell.
     *
     * @param integer $size Size of font.
     *
     * @since  v1.0
     * @return self
     */
    public function setFontSize(int $size) : self
    {

        // Create font if there is no present.
        if ($this->font === null) {
            $this->setFont();
        }

        // Set its size.
        $this->font->setSize($size);

        return $this;
    }

    /**
     * Setter for font color of this Cell.
     *
     * @since  v1.0
     * @return self
     */
    public function setFontColor() : self
    {

        // Create font if there is no present.
        if ($this->font === null) {
            $this->setFont();
        }

        // Set its color.
        $this->font->setColor(...func_get_args());

        return $this;
    }

    /**
     * Checks if style has defined align (vertical or horizontal).
     *
     * @param string $which Values: `both`, `h` or `v`.
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasAlign(string $which = 'both') : bool
    {

        if ($which === 'both') {
            $logic = ( is_null($this->hAlign) === true && is_null($this->vAlign) === true );
        } elseif ($which === 'h') {
            $logic = ( is_null($this->hAlign) === true );
        } elseif ($which === 'v') {
            $logic = ( is_null($this->vAlign) === true );
        } else {
            die('throw hf934hq4');
        }

        return ( ! $logic );
    }

    /**
     * Setter for align of this Cell.
     *
     * @param string $align Align for cell (eg. L, C, CC, CB, LT).
     *
     * @since  v1.0
     * @return self
     */
    public function setAlign(string $align) : self
    {

        // Check.
        if (strlen($align) > 2 || strlen($align) < 1) {
            die('throw 9fj3jf9pwje092');
        }

        // Define vertical if lenght is 2.
        if (strlen($align) === 2) {
            $this->setValign(substr($align, 1, 1));
        }

        // Always define horizontal.
        $this->setHalign(substr($align, 0, 1));

        return $this;
    }

    /**
     * Setter for horizontal align of this Cell.
     *
     * @param string $align Desired horizontal align.
     *
     * @since  v1.0
     * @throws HorizontalAlignOtosetException On wrong horizontal align.
     * @return self
     */
    public function setHalign(?string $align = null) : self
    {

        $this->testLock();

        // Short way.
        if ($align === ' ' || empty($align) === true) {
            $this->hAlign = null;
        }

        // Lvd.
        $possible = [
            'center' => [ 'center', 'C', 'c', 'middle', 'M', 'm' ],
            'left'   => [ 'left', 'L', 'l' ],
            'right'  => [ 'right', 'R', 'r' ],
        ];

        if (in_array($align, $possible['center']) === true) {
            $this->hAlign = 'center';
        } elseif (in_array($align, $possible['left']) === true) {
            $this->hAlign = 'left';
        } elseif (in_array($align, $possible['right']) === true) {
            $this->hAlign = 'right';
        } else {

            $allPossible = array_merge($possible['center'], $possible['top'], $possible['bottom']);
            throw new HorizontalAlignOtosetException($allPossible, $align);
        }

        return $this;
    }

    /**
     * Setter for vertical align of this Cell.
     *
     * @param string $align Desired vertical align.
     *
     * @since  v1.0
     * @throws VerticalAlignOtosetException On wrong vertical align.
     * @return self
     */
    public function setValign(string $align) : self
    {

        $this->testLock();

        // Short way.
        if ($align === ' ' || empty($align) === true) {
            $this->vAlign = null;
        }

        // Lvd.
        $possible = [
            'center' => [ 'center', 'C', 'c', 'middle', 'M', 'm' ],
            'top'    => [ 'top', 'T', 't' ],
            'bottom' => [ 'bottom', 'B', 'b' ],
        ];

        if (in_array($align, $possible['center']) === true) {
            $this->vAlign = 'center';
        } elseif (in_array($align, $possible['top']) === true) {
            $this->vAlign = 'top';
        } elseif (in_array($align, $possible['bottom']) === true) {
            $this->vAlign = 'bottom';
        } else {

            $allPossible = array_merge($possible['center'], $possible['top'], $possible['bottom']);
            throw new VerticalAlignOtosetException($allPossible, $align);
        }

        return $this;
    }

    /**
     * Getter for Cell align.
     *
     * ## Retrun value.
     * Array with two keys 'h' and 'v'.
     *
     * @since  v1.0
     * @return array
     */
    public function getAlign() : array
    {

        return [
            'h' => $this->hAlign,
            'v' => $this->vAlign
        ];
    }

    /**
     * Setter for Style lock (locked Style can't be changed).
     *
     * @param boolean $lock Set true to set lock, false otherwise.
     *
     * @since  v1.0
     * @return self
     */
    public function setLock(bool $lock) : self
    {

        $this->lock = $lock;

        return $this;
    }

    /**
     * Getter for lock (locked Style can't be changed).
     *
     * @since  v1.0
     * @return boolean
     */
    public function getLock() : bool
    {

        return $this->lock;
    }

    /**
     * Check if Style is locked and throw if it is (locked Style can't be changed).
     *
     * @since  v1.0
     * @throws StyleLockedException When Style is locked.
     * @return void
     */
    private function testLock() : void
    {

        if ($this->getLock() === false) {
            return;
        }

        throw new StyleLockedException();
    }

    /**
     * Return signature of this Style - to use it in Styles collection.
     *
     * @since  v1.0
     * @return string
     */
    public function getSignature() : string
    {

        // Lvd.
        $result = '';

        // Prepare style result.
        if ($this->fill !== null) {
            $result .= $this->fill->getSignature();
        }
        if ($this->font !== null) {
            $result .= $this->font->getSignature();
        }
        $result .= 'hAlign:' . $this->hAlign;
        $result .= 'vAlign:' . $this->vAlign;

        return $result;
    }
}
