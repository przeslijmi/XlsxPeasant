<?php declare(strict_types=1);

namespace Przeslijmi\XlsxGenerator;

use Przeslijmi\XlsxGenerator\Items\Book;
use Przeslijmi\XlsxGenerator\Items\Color;
use Przeslijmi\XlsxGenerator\Items\Fill;
use Przeslijmi\XlsxGenerator\Items\Collections\SharedStrings;
use Przeslijmi\XlsxGenerator\Items\Style;
use Przeslijmi\XlsxGenerator\Items\Collections\Styles;
use Przeslijmi\XlsxGenerator\Xml;
use Przeslijmi\XlsxGenerator\Xmls;
use stdClass;
use ZipArchive;

/**
 * XLSX sheets generator.
 */
class Xlsx
{

    /**
     * Collection of XML files.
     *
     * @var Xmls
     */
    private $xmls;

    /**
     * Top item object.
     *
     * @var Book
     */
    private $book;

    /**
     * Collection of values and value parts.
     *
     * @var SharedStrings
     */
    private $sharedStrings;

    /**
     * Collection of Style objects.
     *
     * @var Styles
     */
    private $styles;

    /**
     * Which Style is going to be used when creating new Cell objects.
     *
     * @var Style
     */
    private $useStyle;

    /**
     * Which Fill is going to be used when creating new Cell objects.
     *
     * @var Fill
     */
    private $useFill;

    /**
     * Which Font is going to be used when creating new Cell objects.
     *
     * @var Font
     */
    private $useFont;

    /**
     * Which align is going to be used when creating new Cell objects (eg. `LC`).
     *
     * @var string
     */
    private $useAlign;

    /**
     * Array with default values for this document.
     *
     * @var array
     */
    private $defaults = [];

    /**
     * Constructor.
     *
     * @since v1.0
     */
    public function __construct()
    {

        // Create standards.
        $this->xmls          = new Xmls($this);
        $this->book          = new Book($this);
        $this->sharedStrings = new SharedStrings($this);
        $this->styles        = new Styles($this);

        // Set defaults.
        $this->defaults = [
            'fontColor' => Color::factory('black'),
            'fontSize'  => 11,
            'fontName'  => 'Calibri',
        ];
    }

    /**
     * Getter for Xmls.
     *
     * @since  v1.0
     * @return Xmls
     */
    public function getXmls() : Xmls
    {

        return $this->xmls;
    }

    /**
     * Getter for Book.
     *
     * @since  v1.0
     * @return Book
     */
    public function getBook() : Book
    {

        return $this->book;
    }

    /**
     * Getter for SharedStrings.
     *
     * @since  v1.0
     * @return SharedStrings
     */
    public function getSharedStrings() : SharedStrings
    {

        return $this->sharedStrings;
    }

    /**
     * Getter for Styles.
     *
     * @since  v1.0
     * @return Styles
     */
    public function getStyles() : Styles
    {

        return $this->styles;
    }

    /**
     * Generates XLSX file.
     *
     * @since  v1.0
     * @return void
     */
    public function generate() : void
    {

        // Create ZIP.
        $zip = new ZipArchive();
        $zip->open('tmp/packed_' . rand(1000, 9999) . ' .xlsx', ZipArchive::CREATE);

        // Add worksheets.
        foreach ($this->getBook()->getSheets() as $sheet) {
            $zip->addFromString(
                'xl\worksheets\sheet' . $sheet->getId() . '.xml',
                $sheet->getXml()->toXml()
            );
        }

        // Add other files.
        $zip->addFromString('[Content_Types].xml', $this->xmls->getContentTypes()->toXml());
        $zip->addFromString('docProps\app.xml', $this->xmls->getDocPropsApp()->toXml());
        $zip->addFromString('docProps\core.xml', $this->xmls->getDocPropsCore()->toXml());
        $zip->addFromString('_rels\.rels', $this->xmls->getRelsRels()->toXml());
        $zip->addFromString('xl\_rels\workbook.xml.rels', $this->xmls->getXlRelsWorkbook()->toXml());
        $zip->addFromString('xl\sharedStrings.xml', $this->xmls->getXlSharedStrings()->toXml());
        $zip->addFromString('xl\styles.xml', $this->xmls->getXlStyles()->toXml());
        $zip->addFromString('xl\theme\theme1.xml', $this->xmls->getXlTheme()->toXml());
        $zip->addFromString('xl\workbook.xml', $this->xmls->getXlWorkbook()->toXml());

        // Close ZIP.
        $zip->close();
    }

    /**
     * Setter for `useStyle`.
     *
     * @param Style $style Style to use in new Cells.
     *
     * @since  v1.0
     * @return self
     */
    public function useStyle(Style $style) : self
    {

        $this->useStyle = $style;

        return $this;
    }

    /**
     * Getter for `useStyle`.
     *
     * @since  v1.0
     * @return null|Style
     */
    public function getStyleToUse() : ?Style
    {

        return $this->useStyle;
    }

    /**
     * Setter for `useFill`.
     *
     * @param Fill $fill Fill to use in new Cells.
     *
     * @since  v1.0
     * @return self
     */
    public function useFill(Fill $fill) : self
    {

        $this->useFill = $fill;

        return $this;
    }

    /**
     * Getter for `useFill`.
     *
     * @since  v1.0
     * @return null|Fill
     */
    public function getFillToUse() : ?Fill
    {

        return $this->useFill;
    }

    /**
     * Setter for `useFont`.
     *
     * @param Font $font Font to use in new Cells.
     *
     * @since  v1.0
     * @return self
     */
    public function useFont(Font $font) : self
    {

        $this->useFont = $font;

        return $this;
    }

    /**
     * Getter for `useFont`.
     *
     * @since  v1.0
     * @return null|Font
     */
    public function getFontToUse() : ?Font
    {

        return $this->useFont;
    }

    /**
     * Setter for `useAlign`.
     *
     * @param string $align Align to use in new Cells (eg. LC, RB).
     *
     * @since  v1.0
     * @return self
     */
    public function useAlign(string $align) : self
    {

        $this->useAlign = $align;

        return $this;
    }

    /**
     * Getter for `useAlign`.
     *
     * @since  v1.0
     * @return null|string
     */
    public function getAlignToUse() : ?string
    {

        return $this->useAlign;
    }

    /**
     * Setter for default value.
     *
     * @param string $name  Setting name.
     * @param mixed  $value Setting value.
     *
     * @since  v1.0
     * @return self
     */
    public function setDefault(string $name, $value) : self
    {

        $this->defaults[$name] = $value;

        return $this;
    }

    /**
     * Getter for default value.
     *
     * @param string $name Setting name.
     *
     * @since  v1.0
     * @return mixed
     */
    public function getDefault(string $name)
    {

        return $this->defaults[$name];
    }
}
