<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items;

use Przeslijmi\Sexceptions\Exceptions\ParamOtosetException;
use Przeslijmi\Sivalidator\RegEx;
use Przeslijmi\XlsxPeasant\Exceptions\HorizontalAlignOtosetException;
use Przeslijmi\XlsxPeasant\Exceptions\StyleLockedException;
use Przeslijmi\XlsxPeasant\Exceptions\VerticalAlignOtosetException;
use Przeslijmi\XlsxPeasant\Items;
use Przeslijmi\XlsxPeasant\Items\Cell;
use Przeslijmi\XlsxPeasant\Items\Color;
use Przeslijmi\XlsxPeasant\Items\ConditionalFormat;
use Przeslijmi\XlsxPeasant\Items\Fill;
use Przeslijmi\XlsxPeasant\Items\Font;
use Przeslijmi\XlsxPeasant\Items\Format;

/**
 * One Style can be used by one or more Cell objects.
 */
class Style extends Items
{

    /**
     * Id of Style (defined later on creation of XLSX file).
     *
     * @var integer
     */
    private $id;

    /**
     * Fill of Style.
     *
     * @var Fill
     */
    private $fill;

    /**
     * Font of Style.
     *
     * @var Font
     */
    private $font;

    /**
     * Horizontal align in Style (left, center, right).
     *
     * @var string
     */
    private $hAlign;

    /**
     * Verical align in Style (top, center, bottom).
     *
     * @var string
     */
    private $vAlign;

    /**
     * Wrap text?
     *
     * @var boolean
     */
    private $wrapText;

    /**
     * Format of this Style.
     *
     * @var Format
     */
    private $format;

    /**
     * Conditional Format of this Style.
     *
     * @var ConditionalFormat
     */
    private $conditionalFormat;

    /**
     * If Style is locked (locked Style can't be changed).
     *
     * @var boolean
     */
    private $lock = false;

    /**
     * Getter for Style ID of this Style.
     *
     * @since  v1.0
     * @return integer
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
     * Setter for id of Style (used by XML files).
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
     * Checks if Style has defined Fill.
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasFill() : bool
    {

        return ! ( $this->fill === null );
    }

    /**
     * Setter for Fill of this Style.
     *
     * @since  v1.0
     * @return self
     */
    public function setFill() : self
    {

        // Test if Style is locked (throw if it is).
        $this->testLock();

        // Save fill.
        $this->fill = Fill::factory(...func_get_args());

        return $this;
    }

    /**
     * Getter for Fill of this Style.
     *
     * @since  v1.0
     * @return ?Fill
     */
    public function getFill() : ?Fill
    {

        return $this->fill;
    }

    /**
     * Checks if Style has defined Font.
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasFont() : bool
    {

        return ! ( $this->font === null );
    }

    /**
     * Setter for Font of this Style.
     *
     * @param null|Font $font Font to use.
     *
     * @since  v1.0
     * @return self
     */
    public function setFont(?Font $font = null) : self
    {

        // Test if Style is locked (throw if it is).
        $this->testLock();

        // Creates Font object (if needed).
        if ($font === null) {
            $font = new Font($this->xlsx);
        }

        // Saves font.
        $this->font = $font;

        return $this;
    }

    /**
     * Getter for Font of this Style.
     *
     * @since  v1.0
     * @return ?Font
     */
    public function getFont() : ?Font
    {

        return $this->font;
    }

    /**
     * Setter for font name of this Style.
     *
     * @param string $name Name of font.
     *
     * @since  v1.0
     * @return self
     */
    public function setFontName(string $name) : self
    {

        // Test if Style is locked (throw if it is).
        $this->testLock();

        // Create font if there is no present.
        if ($this->font === null) {
            $this->setFont();
        }

        // Set its name.
        $this->font->setName($name);

        return $this;
    }

    /**
     * Setter for font size of this Style.
     *
     * @param integer $size Size of font.
     *
     * @since  v1.0
     * @return self
     */
    public function setFontSize(int $size) : self
    {

        // Test if Style is locked (throw if it is).
        $this->testLock();

        // Create font if there is no present.
        if ($this->font === null) {
            $this->setFont();
        }

        // Set its size.
        $this->font->setSize($size);

        return $this;
    }

    /**
     * Setter for font color of this Style.
     *
     * @since  v1.0
     * @return self
     */
    public function setFontColor() : self
    {

        // Test if Style is locked (throw if it is).
        $this->testLock();

        // Create font if there is no present.
        if ($this->font === null) {
            $this->setFont();
        }

        // Set its color.
        $this->font->setColor(...func_get_args());

        return $this;
    }

    /**
     * Checks if Style has defined align (vertical or horizontal).
     *
     * @param string $which Optional, `both`. Possible values: `both`, `h` or `v`.
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasAlign(string $which = 'both') : bool
    {

        // RegEx test.
        RegEx::ifMatches($which, '/^(both|h|v)$/');

        // Send answer.
        if ($which === 'both') {
            $logic = ( is_null($this->hAlign) === true && is_null($this->vAlign) === true );
        } elseif ($which === 'h') {
            $logic = ( is_null($this->hAlign) === true );
        } elseif ($which === 'v') {
            $logic = ( is_null($this->vAlign) === true );
        }

        return ( ! $logic );
    }

    /**
     * Setter for align of this Style.
     *
     * @param string $align Align for Style (eg. L, C, CC, CB, LT).
     *
     * @since  v1.0
     * @return self
     */
    public function setAlign(string $align) : self
    {

        // Test if Style is locked (throw if it is).
        $this->testLock();

        // RegEx test.
        RegEx::ifMatches($align, '/^(L|C|M|R){1}(T|C|M|B)?$/');

        // Define vertical if lenght is 2.
        if (strlen($align) === 2) {
            $this->setValign(substr($align, 1, 1));
        }

        // Always define horizontal.
        $this->setHalign(substr($align, 0, 1));

        return $this;
    }

    /**
     * Setter for horizontal align of this Style.
     *
     * @param string $align Desired horizontal align.
     *
     * @since  v1.0
     * @throws HorizontalAlignOtosetException On wrong horizontal align.
     * @return self
     */
    public function setHalign(?string $align = null) : self
    {

        // Test if Style is locked (throw if it is).
        $this->testLock();

        // Short way.
        if (empty(trim((string) $align)) === true) {
            $this->hAlign = null;
            return $this;
        }

        // Lvd.
        $possible = [
            'center' => [ 'center', 'C', 'c', 'middle', 'M', 'm' ],
            'left'   => [ 'left', 'L', 'l' ],
            'right'  => [ 'right', 'R', 'r' ],
        ];

        // Set.
        if (in_array($align, $possible['center']) === true) {
            $this->hAlign = 'center';
        } elseif (in_array($align, $possible['left']) === true) {
            $this->hAlign = 'left';
        } elseif (in_array($align, $possible['right']) === true) {
            $this->hAlign = 'right';
        } else {

            $allPossible = array_merge($possible['center'], $possible['left'], $possible['right']);
            throw new HorizontalAlignOtosetException($allPossible, $align);
        }

        return $this;
    }

    /**
     * Setter for vertical align of this Style.
     *
     * @param string $align Desired vertical align.
     *
     * @since  v1.0
     * @throws VerticalAlignOtosetException On wrong vertical align.
     * @return self
     */
    public function setValign(?string $align = null) : self
    {

        // Test if Style is locked (throw if it is).
        $this->testLock();

        // Short way.
        if (empty(trim((string) $align)) === true) {
            $this->vAlign = null;
            return $this;
        }

        // Lvd.
        $possible = [
            'center' => [ 'center', 'C', 'c', 'middle', 'M', 'm' ],
            'top'    => [ 'top', 'T', 't' ],
            'bottom' => [ 'bottom', 'B', 'b' ],
        ];

        // Set.
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
     * Getter for Style align.
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
     * Checks if Style has defined text wrap.
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasWrapText() : bool
    {

        return ( ! is_null($this->wrapText) );
    }

    /**
     * Setter for align of this Style.
     *
     * @param boolean $wrapText WrapText for Style.
     *
     * @since  v1.0
     * @return self
     */
    public function setWrapText(bool $wrapText) : self
    {

        // Test if Style is locked (throw if it is).
        $this->testLock();

        $this->wrapText = $wrapText;

        return $this;
    }

    /**
     * Getter for Style wrap text.
     *
     * @since  v1.0
     * @return boolean
     */
    public function getWrapText() : bool
    {

        return $this->wrapText;
    }

    /**
     * Checks if Style has format defined.
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasFormat() : bool
    {

        return ( ! is_null($this->format) );
    }

    /**
     * Setter for format of this Style.
     *
     * @param Format $format Format of this Style.
     *
     * @since  v1.0
     * @return self
     */
    public function setFormat(Format $format) : self
    {

        // Test if Style is locked (throw if it is).
        $this->testLock();

        $this->format = $format;

        return $this;
    }

    /**
     * Getter for format of this Style.
     *
     * @since  v1.0
     * @return Format
     */
    public function getFormat() : Format
    {

        return $this->format;
    }

    /**
     * Checks if Style has conditional format defined.
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasConditionalFormat() : bool
    {

        return ( ! is_null($this->conditionalFormat) );
    }

    /**
     * Setter for conditional format of this Style.
     *
     * @param ConditionalFormat $conditionalFormat Conditional format of this Style.
     *
     * @since  v1.0
     * @return self
     */
    public function setConditionalFormat(ConditionalFormat $conditionalFormat) : self
    {

        // Test if Style is locked (throw if it is).
        $this->testLock();

        $this->conditionalFormat = $conditionalFormat;

        return $this;
    }

    /**
     * Getter for conditional format of this Style.
     *
     * @since  v1.0
     * @return ConditionalFormat
     */
    public function getConditionalFormat() : ConditionalFormat
    {

        return $this->conditionalFormat;
    }

    /**
     * Setter for Style lock (locked Style can't be changed).
     *
     * @param boolean $lock Optional, true. Set true to set lock, false otherwise.
     *
     * @since  v1.0
     * @return self
     */
    public function setLock(bool $lock = true) : self
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

        // Shortcut.
        if ($this->getLock() === false) {
            return;
        }

        throw new StyleLockedException();
    }

    /**
     * Return signature of this Style - to use it in Styles collection for hasing.
     *
     * @since  v1.0
     * @return string
     */
    public function getSignature() : string
    {

        // Lvd.
        $result = '';

        // Prepare Style result.
        if ($this->fill !== null) {
            $result .= $this->fill->getSignature();
        }

        if ($this->font !== null) {
            $result .= $this->font->getSignature();
        }

        $result .= 'hAlign:' . $this->hAlign;
        $result .= 'vAlign:' . $this->vAlign;
        $result .= 'wrapText:' . $this->wrapText;

        if ($this->format !== null) {
            $result .= $this->format->getSignature();
        }
        if ($this->conditionalFormat !== null) {
            $result .= $this->conditionalFormat->getSignature();
        }

        return $result;
    }
}
