<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Xmls\Common;

use Przeslijmi\XlsxPeasant\Items\Font;
use Przeslijmi\XlsxPeasant\Xlsx;

/**
 * XML nodes creator for Font object.
 */
class FontXml
{

    /**
     * Parent XLSx file.
     *
     * @var Xlsx
     */
    private $xlsx;

    /**
     * Font to create for.
     *
     * @var Xlsx
     */
    private $font;

    /**
     * If this is for SharedString XML or WorkSheet XML (there are small differences).
     *
     * @var boolean
     */
    private $forSharedStrings;

    /**
     * Constructor.
     *
     * @param Xlsx $xlsx Xlsx to import to this XML.
     * @param Font $font Font to create for.
     *
     * @since v1.0
     */
    public function __construct(Xlsx $xlsx, Font $font)
    {

        $this->xlsx = $xlsx;
        $this->font = $font;
    }

    /**
     * Setter for `this->forSharedStrings`.
     *
     * @param boolean $forSharedStrings If this is for SharedString XML or WorkSheet XML.
     *
     * @since  v1.0
     * @return self
     */
    public function setForSharedStrings(bool $forSharedStrings) : self
    {

        $this->forSharedStrings = $forSharedStrings;

        return $this;
    }

    /**
     * Create XML nodes.
     *
     * @since  v1.0
     * @return array
     */
    public function toXmlArray() : array
    {

        // Lvd.
        $array = [
            '@@' => [
            ],
        ];

        // Bold, italic and underline.
        if ($this->font->hasBold() === true && $this->font->isBold() === true) {
            $array['@@']['b'] = null;
        }
        if ($this->font->hasItalic() === true && $this->font->isItalic() === true) {
            $array['@@']['i'] = null;
        }
        if ($this->font->hasUnderline() === true && $this->font->isUnderline() === true) {
            $array['@@']['u'] = null;
        }

        // Lvd font name tag.
        $nameTag = ( ( $this->forSharedStrings === true ) ? 'rFont' : 'name' );

        // Get values.
        if ($this->font->hasName() === true) {
            $name = $this->font->getName();
        } else {
            $name = $this->xlsx->getDefault('fontName');
        }
        if ($this->font->hasSize() === true) {
            $size = $this->font->getSize();
        } else {
            $size = $this->xlsx->getDefault('fontSize');
        }
        if ($this->font->hasColor() === true) {
            $color = $this->font->getColor()->get();
        } else {
            $color = $this->xlsx->getDefault('fontColor')->get();
        }

        // Main definition.
        $array['@@']['sz']      = [
            '@val' => $size,
        ];
        $array['@@']['color']   = [
            '@rgb' => $color,
        ];
        $array['@@'][$nameTag]  = [
            '@val' => $name,
        ];
        $array['@@']['family']  = [
            '@val' => '2',
        ];
        $array['@@']['charset'] = [
            '@val' => '238',
        ];

        return $array;
    }
}
