<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Reader;

use Przeslijmi\XlsxPeasant\Reader;
use DOMDocument;

/**
 * Parent object for each XML inside read XLSX file.
 *
 * If this XML files have corresponding rels XML file - it is included inside
 * no as separate object.
 */
abstract class XmlFile
{

    /**
     * Parent Reader instance.
     *
     * @var Reader
     */
    private $reader;

    /**
     * Uri of XML file.
     *
     * @var string
     */
    private $fileUri;

    /**
     * XML contents object.
     *
     * @var DOMDocument
     */
    protected $contents;

    /**
     * XML rels object.
     *
     * @var DOMDocument
     */
    protected $rels;

    /**
     * Constructor.
     *
     * @param string $fileUri Uri of XML file.
     * @param Reader $reader  Reader parent obj.
     *
     * @since v1.0
     */
    public function __construct(string $fileUri, Reader $reader)
    {

        $this->reader = $reader;
        $this->setFileUri($fileUri);
        $this->readXml();
    }

    /**
     * Getter for `reader`.
     *
     * @since  v1.0
     * @return Reader
     */
    public function getReader() : Reader
    {

        return $this->reader;
    }

    /**
     * Setter for `fileUri`.
     *
     * @param string $fileUri Uri of XML file.
     *
     * @since  v1.0
     * @throws FileDonoexException When file does not exists.
     * @return self
     */
    public function setFileUri(string $fileUri) : self
    {

        // Throw.
        if (file_exists($fileUri) === false) {
            throw new FileDonoexException('XmlFileAsPartOfXlsxArchive', $fileUri);
        }

        // Save.
        $this->fileUri = $fileUri;

        return $this;
    }

    /**
     * Getter for `fileUri`.
     *
     * @since  v1.0
     * @return string
     */
    public function getFileUri() : string
    {

        return $this->fileUri;
    }

    /**
     * Reads in XML as object.
     *
     * @since  v1.0
     * @return self
     */
    private function readXml() : self
    {

        $this->contents = new DOMDocument();
        $this->contents->loadXML(file_get_contents($this->fileUri));

        return $this;
    }

    /**
     * Raads rels XML as object.
     *
     * @param string $relsFileUri URI of rels file inside unpacked XLSX file.
     *
     * @version v1.0
     * @return  self
     */
    public function addRels(string $relsFileUri) : self
    {

        $this->rels = new DOMDocument();
        $this->rels->loadXML(file_get_contents($relsFileUri));

        return $this;
    }
}
