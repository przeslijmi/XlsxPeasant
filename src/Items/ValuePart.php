<?php declare(strict_types=1);

namespace Przeslijmi\XlsxGenerator\Items;

use Przeslijmi\XlsxGenerator\Items\Cell;
use Przeslijmi\XlsxGenerator\Items\Font;

/**
 * One part of Cell value (can be only one in Cell or more).
 */
class ValuePart
{

    /**
     * Parent Cell.
     *
     * @var Cell
     */
    private $cell;

    /**
     * Actual contents of the value part.
     *
     * @var string
     */
    private $contents;

    /**
     * Font used by this part.
     *
     * @var Font
     */
    private $font;

    /**
     * Constructor.
     *
     * @param Cell      $cell     Cell parent.
     * @param string    $contents Actual contents of part..
     * @param null|Font $font     Optional Font to use in this part.
     *
     * @since v1.0
     */
    public function __construct(Cell $cell, string $contents, ?Font $font = null)
    {

        // Lvd.
        $this->cell     = $cell;
        $this->contents = $contents;

        // Add Font if ordered.
        if ($font !== null) {
            $this->font = $font;
        }
    }

    /**
     * If this part has Font defined.
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasFont() : bool
    {

        return ! ( $this->font === null );
    }

    /**
     * Getter for Font.
     *
     * @since  v1.0
     * @return null|Font
     */
    public function getFont() : ?Font
    {

        return $this->font;
    }

    /**
     * Getter for merged Font (valuePart Font + cell Font).
     *
     * @since  v1.0
     * @return null|Font
     */
    public function getFontMerged() : ?Font
    {

        // If Cell has not - return from Part.
        if ($this->cell->hasStyle() === true && $this->cell->getStyle()->hasFont() === false) {
            return $this->getFont();
        }

        // If Part has not - return from Cell.
        if ($this->hasFont() === false) {

            if ($this->cell->hasStyle() === true) {
                return $this->cell->getStyle()->getFont();
            }

            return null;
        }

        // If both have fonts - call to merge.
        return Font::factoryMerged($this->cell->getStyle()->getFont(), $this->getFont());
    }

    /**
     * Getter for contents of this part.
     *
     * @since  v1.0
     * @return string
     */
    public function getContents() : string
    {

        return $this->contents;
    }

    /**
     * Return signature of this ValuePart - to use it in SharedStrings collection.
     *
     * @since  v1.0
     * @return string
     */
    public function getSignature() : string
    {

        // Lvd.
        $signature = 'contents:' . $this->contents;

        // Add Font signature if needed.
        if ($this->hasFont() === true) {
            $signature = 'font:' . $this->font->getSignature();
        }

        return $signature;
    }
}
