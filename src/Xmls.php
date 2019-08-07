<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant;

use Przeslijmi\XlsxPeasant\Xlsx;
use Przeslijmi\XlsxPeasant\Xmls\ContentTypes;
use Przeslijmi\XlsxPeasant\Xmls\DocPropsApp;
use Przeslijmi\XlsxPeasant\Xmls\DocPropsCore;
use Przeslijmi\XlsxPeasant\Xmls\RelsRels;
use Przeslijmi\XlsxPeasant\Xmls\XlRelsWorkbook;
use Przeslijmi\XlsxPeasant\Xmls\XlSharedStrings;
use Przeslijmi\XlsxPeasant\Xmls\XlStyles;
use Przeslijmi\XlsxPeasant\Xmls\XlTheme;
use Przeslijmi\XlsxPeasant\Xmls\XlWorkbook;

/**
 * Collection of XML files to generate (only those that are non-multiple).
 *
 * Multiple files (eg. tables, sheets, sheets rels) are managed by Book object.
 */
class Xmls
{

    /**
     * XML file for `[Content_Types].xml`.
     *
     * @var ContentTypes
     */
    private $contentTypes;

    /**
     * XML file for `docProps\app.xml`.
     *
     * @var DocPropsApp
     */
    private $docPropsApp;

    /**
     * XML file for `docProps\core.xml`.
     *
     * @var DocPropsCore
     */
    private $docPropsCore;

    /**
     * XML file for `_rels\.rels`.
     *
     * @var RelsRels
     */
    private $relsRels;

    /**
     * XML file for `xl\_rels\workbook.xml.rels`.
     *
     * @var XlTheme
     */
    private $xlRelsWorkbook;

    /**
     * XML file for `xl\styles.xml`.
     *
     * @var XlStyles
     */
    private $xlStyles;

    /**
     * XML file for `xl\sharedStrings.xml`.
     *
     * @var XlSharedStrings
     */
    private $xlSharedStrings;

    /**
     * XML file for `xl\theme\theme1.xml`.
     *
     * @var XlTheme
     */
    private $xlTheme;

    /**
     * XML file for `xl\workbook.xml`.
     *
     * @var XlWorkbook
     */
    private $xlWorkbook;

    /**
     * Constrtor.
     *
     * @param Xlsx $xlsx Xlsx document.
     *
     * @since v1.0
     */
    public function __construct(Xlsx $xlsx)
    {

        // Create files.
        $this->contentTypes    = new ContentTypes($xlsx);
        $this->docPropsApp     = new DocPropsApp($xlsx);
        $this->docPropsCore    = new DocPropsCore();
        $this->relsRels        = new RelsRels();
        $this->xlRelsWorkbook  = new XlRelsWorkbook($xlsx);
        $this->xlStyles        = new XlStyles($xlsx);
        $this->xlSharedStrings = new XlSharedStrings($xlsx);
        $this->xlTheme         = new XlTheme();
        $this->xlWorkbook      = new XlWorkbook($xlsx);
    }

    /**
     * Getter for `contentTypes` XML.
     *
     * @since  v1.0
     * @return ContentTypes
     */
    public function getContentTypes() : ContentTypes
    {

        return $this->contentTypes;
    }

    /**
     * Getter for `docPropsApp` XML.
     *
     * @since  v1.0
     * @return DocPropsApp
     */
    public function getDocPropsApp() : DocPropsApp
    {

        return $this->docPropsApp;
    }

    /**
     * Getter for `docPropsCore` XML.
     *
     * @since  v1.0
     * @return DocPropsCore
     */
    public function getDocPropsCore() : DocPropsCore
    {

        return $this->docPropsCore;
    }

    /**
     * Getter for `relsRels` XML.
     *
     * @since  v1.0
     * @return RelsRels
     */
    public function getRelsRels() : RelsRels
    {

        return $this->relsRels;
    }

    /**
     * Getter for `xlRelsWorkbook` XML.
     *
     * @since  v1.0
     * @return XlRelsWorkbook
     */
    public function getXlRelsWorkbook() : XlRelsWorkbook
    {

        return $this->xlRelsWorkbook;
    }

    /**
     * Getter for `xlStyles` XML.
     *
     * @since  v1.0
     * @return XlStyles
     */
    public function getXlStyles() : XlStyles
    {

        return $this->xlStyles;
    }

    /**
     * Getter for `xlSharedStrings` XML.
     *
     * @since  v1.0
     * @return XlSharedStrings
     */
    public function getXlSharedStrings() : XlSharedStrings
    {

        return $this->xlSharedStrings;
    }

    /**
     * Getter for `xlTheme` XML.
     *
     * @since  v1.0
     * @return XlTheme
     */
    public function getXlTheme() : XlTheme
    {

        return $this->xlTheme;
    }

    /**
     * Getter for `xlWorkbook` XML.
     *
     * @since  v1.0
     * @return XlWorkbook
     */
    public function getXlWorkbook() : XlWorkbook
    {

        return $this->xlWorkbook;
    }
}
