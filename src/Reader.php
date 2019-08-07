<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant;

use Przeslijmi\XlsxPeasant\Reader\Creator;
use Przeslijmi\XlsxPeasant\Reader\XmlFile;
use Przeslijmi\XlsxPeasant\Reader\XmlFile\XlSharedStrings;
use Przeslijmi\XlsxPeasant\Reader\XmlFile\XlWorkbook;
use Przeslijmi\XlsxPeasant\Reader\XmlFile\XlWorksheet;
use Przeslijmi\XlsxPeasant\Reader\XmlFile\XlTable;
use Przeslijmi\XlsxPeasant\Xlsx;
use XMLReader;
use ZipArchive;

class Reader
{

    /**
     * Uri of XLSx file.
     *
     * @var string
     */
    private $xlsxFileUri;

    private $xlSharedStrings;
    private $xlWorkbook;
    private $xlWorksheets = [];
    private $xlTables = [];

    /**
     * List of all unpacked files.
     *
     * @var string[]
     */
    private $allFiles = [];

    /**
     * URI of directory to which files were unzipped.
     *
     * @var string
     */
    private $unzipUri;

    /**
     * Constructor.
     *
     * @param string $xlsxFileUri Uri of XLSx file.
     *
     * @since v1.0
     */
    public function __construct(string $xlsxFileUri)
    {

        // Check if file exists.
        if (file_exists($xlsxFileUri) === false) {
            die('file donoex ' . $xlsxFileUri . ' in ' . getcwd());
        }

        // Save.
        $this->xlsxFileUri = $xlsxFileUri;
        $this->unzipUri    = sys_get_temp_dir() . '\\_stolem_xlsxReader\\' . rand(10000, 99999) . '\\';
    }

    /**
     * Main working function - reads file into XLSX object.
     *
     * @since  v1.0
     * @return Xlsx
     */
    public function readIn()
    {

        // Call to unpack ZIP file (makes `$this->allFiles` nonempty).
        $this->unpack();

        // Read in Workbook.
        $pattern = '/(xl)(\\\\|\\/)(workbook.xml)$/';
        foreach (preg_grep($pattern, $this->allFiles) as $fileUri) {
            $this->setXlWorkbook($fileUri);
        }

        // Read in SharedStrings.
        $pattern = '/(xl)(\\\\|\\/)(sharedStrings.xml)$/';
        foreach (preg_grep($pattern, $this->allFiles) as $fileUri) {
            $this->setXlSharedStrings($fileUri);
        }

        // Read in Sheets.
        $pattern = '/(xl)(\\\\|\\/)(worksheets)(\\\\|\\/)(sheet)(\\d)+(.xml)$/';
        foreach (preg_grep($pattern, $this->allFiles) as $fileUri) {
            $this->addXlWorksheet($fileUri);
        }

        // Read in Tables.
        $pattern = '/(xl)(\\\\|\\/)(tables)(\\\\|\\/)(table)(\\d)+(.xml)$/';
        foreach (preg_grep($pattern, $this->allFiles) as $fileUri) {
            $this->addXlTable($fileUri);
        }

        $creator = new Creator($this);
        $xlsx = $creator->create();

        return $xlsx;
    }

    public function setXlSharedStrings(string $fileUri) : self
    {

        if ($this->xlSharedStrings !== null) {
            die('shared strings exists already');
        }

        $this->xlSharedStrings = new XlSharedStrings($fileUri, $this);

        return $this;
    }

    public function getXlSharedStrings() : XlSharedStrings
    {

        return $this->xlSharedStrings;
    }

    public function setXlWorkbook(string $fileUri) : self
    {

        if ($this->xlWorkbook !== null) {
            die('workbook exists already');
        }

        $this->xlWorkbook = new XlWorkbook($fileUri, $this);

        return $this;
    }

    public function getXlWorkbook() : XlWorkbook
    {

        return $this->xlWorkbook;
    }

    public function addXlWorksheet(string $fileUri) : self
    {

        $this->xlWorksheet[] = new XlWorksheet($fileUri, $this);

        return $this;
    }

    public function getXlWorksheets() : array
    {

        return $this->xlWorksheet;
    }

    public function addXlTable(string $fileUri) : self
    {

        $this->xlTables[] = new XlTable($fileUri, $this);

        return $this;
    }

    public function getXlTables() : array
    {

        return $this->xlTables;
    }

    /**
     * Unpacks ZIP into files.
     *
     * @since  v1.0
     * @return self
     */
    private function unpack() : self
    {

        // Unpack.
        $zip = new ZipArchive();
        $zip->open($this->xlsxFileUri);
        $zip->extractTo($this->unzipUri);
        $zip->close();

        // Get all files.
        $this->allFiles = $this->getFilesRecursively();

        return $this;
    }

    /**
     * Scans directory recursively to get list of all files.
     *
     * @param null|string $dir Optional directory path. If not given $this->unzipUri is used.
     *
     * @since  v1.0
     * @return array
     */
    private function getFilesRecursively(?string $dir = null) : array
    {

        // If no dir given take from instance.
        if (is_null($dir)) {
            $dir = $this->unzipUri;
        }

        // Lvd.
        $sep     = '\\';
        $results = [];

        // Scan directory to find for filex.
        foreach (scandir($dir) as $name) {

            // Ignore
            if ($name === '.' || $name === '..') {
                continue;
            }

            // Create real path for this file.
            $path = rtrim($dir, $sep) . $sep . $name;

            // Serve path and add all files.
            if (is_dir($path) === true) {
                $results = array_merge($results, $this->getFilesRecursively($path . $sep));
                continue;
            }

            // Serve normal file.
            $results[] = $path;
        }

        return $results;
    }
}
