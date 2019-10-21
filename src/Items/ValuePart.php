<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items;

use Przeslijmi\XlsxPeasant\Items\Cell;
use Przeslijmi\XlsxPeasant\Items\Font;
use Przeslijmi\XlsxPeasant\Items\FontFactory;

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
     * @var string|integer|float
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
     * @param Cell                 $cell     Cell parent.
     * @param string|integer|float $contents Actual contents of part..
     * @param null|Font            $font     Optional Font to use in this part.
     *
     * @since v1.0
     */
    public function __construct(Cell $cell, $contents, ?Font $font = null)
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

        // Lvd.
        $cellHasFont      = ( ( $this->cell->hasStyle() === true ) ? $this->cell->getStyle()->hasFont() : false );
        $valuePartHasFont = $this->hasFont();

        // If Cell has no Font, but ValuePart has - return from ValuePart.
        if ($cellHasFont === false && $valuePartHasFont === true) {
            return $this->getFont();
        }

        // If ValuePart has no Font, but Cell has it - return from Cell.
        if ($valuePartHasFont === false && $cellHasFont === true) {
            return $this->cell->getStyle()->getFont();
        }

        // If none has Font - return default Font.
        if ($cellHasFont === false && $valuePartHasFont === false) {
            return $this->cell->getXlsx()->getDefaultFont();
        }

        // Finally if both have fonts - call to merge.
        return FontFactory::makeMerged($this->cell->getStyle()->getFont(), $this->getFont());
    }

    /**
     * Getter for contents of this part.
     *
     * @since  v1.0
     * @return string|integer|float|array
     */
    public function getContents()
    {

        // Return as is for numeric.
        if (in_array(gettype($this->contents), [ 'integer', 'float', 'double' ]) === true) {
            return $this->contents;
        }

        // Return as array when text is not trimmed.
        if (trim($this->contents) !== $this->contents) {
            return [
                '@xml:space' => 'preserve',
                '@@' => $this->contents,
            ];
        }

        // Otherwise return as string.
        return $this->contents;
    }

    /**
     * Getter for contents of this part but only as scalar.
     *
     * @since  v1.0
     * @return string|integer|float
     */
    public function getContentsAsScalar()
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
        $signature = 'contents:' . (string) $this->contents;

        // Add Font signature if needed.
        if ($this->hasFont() === true) {
            $signature = 'font:' . $this->font->getSignature();
        }

        return $signature;
    }
}
