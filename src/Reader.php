<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Sexceptions\Exceptions\DirIsEmptyException;
use Przeslijmi\Sexceptions\Exceptions\FileDonoexException;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Sexceptions\Exceptions\ObjectDonoexException;
use Przeslijmi\XlsxPeasant\Reader\XmlFile;
use Przeslijmi\XlsxPeasant\Reader\XmlFile\XlSharedStrings;
use Przeslijmi\XlsxPeasant\Reader\XmlFile\XlTable;
use Przeslijmi\XlsxPeasant\Reader\XmlFile\XlWorkbook;
use Przeslijmi\XlsxPeasant\Reader\XmlFile\XlWorksheet;
use Przeslijmi\XlsxPeasant\Xlsx;
use Throwable;
use XMLReader;
use ZipArchive;

/**
 * Reads XLSX file and delivers it as ready to use Xlsx Object.
 *
 * ## Usage example
 * ```
 * $xlsx = (new Przeslijmi\XlsxPeasant\Reader($xlsxFileUri))->readIn();
 * ```
 */
class Reader
{

    /**
     * Final XLSX file to be returned by this Reader.
     *
     * @var Xlsx
     */
    private $xlsx;

    /**
     * Uri of XLSx file to read.
     *
     * @var string
     */
    private $xlsxFileUri;

    /**
     * XML SharedStrings as object.
     *
     * @var object
     */
    private $xlSharedStrings;

    /**
     * XML Workbook as object.
     *
     * @var object
     */
    private $xlWorkbook;

    /**
     * XML Worksheets as elements of array.
     *
     * @var object[]
     */
    private $xlWorksheets = [];

    /**
     * XML Tables as elements of array.
     *
     * @var object[]
     */
    private $xlTables = [];

    /**
     * List of all unpacked files.
     *
     * ## Value example
     * ```
     * [
     *     'C:\FullPath\[Content_Types].xml',
     *     'C:\FullPath\_rels\.rels',
     *     \\ ...
     * ]
     * ```
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
     * @since  v1.0
     * @throws FileDonoexException Wher file to be read is not existing.
     * @throws ClassFopException   When creating XLSX Reader object fails.
     */
    public function __construct(string $xlsxFileUri)
    {

        // Do job.
        try {

            // Check if file exists.
            if (file_exists($xlsxFileUri) === false) {
                throw new FileDonoexException('readingXlsxFile', $xlsxFileUri);
            }

            // Save.
            $this->xlsxFileUri = $xlsxFileUri;
            $this->unzipUri    = ''
                . rtrim(sys_get_temp_dir(), '/\\/')
                . '/_stolem_xlsxReader/'
                . rand(10000, 99999);

        } catch (Throwable $thr) {
            throw new ClassFopException('creatingXlsxReader', $thr);
        }
    }

    /**
     * Main working function - reads file into XLSX object.
     *
     * @since  v1.0
     * @throws ClassFopException If any stage of creating Xlsx object from Xlsx file fails.
     * @return Xlsx
     */
    public function readIn()
    {

        // Call to unpack ZIP file (makes `$this->allFiles` nonempty).
        try {
            $this->unpack();
        } catch (Throwable $thr) {
            throw (new ClassFopException('unpackingXlsxSheet', $thr))
                ->addInfo('xlsxFileUri', $this->xlsxFileUri);
        }

        // Call to open all unpacked XML files and create readeable objects from them.
        try {
            $this->readXmlFilesIntoObjects();
        } catch (Throwable $thr) {
            throw (new ClassFopException('readingXmlsFromXlxsFileIntoObjects', $thr))
                ->addInfo('xlsxFileUri', $this->xlsxFileUri);
        }

        // Create final XLSx object.
        try {

            // Create.
            $this->xlsx = new Xlsx();

            // Fill up.
            $this->createXlsx();

        } catch (Throwable $thr) {
            throw (new ClassFopException('creatingXlsxFromReader', $thr))
                ->addInfo('xlsxFileUri', $this->xlsxFileUri);
        }

        return $this->xlsx;
    }

    /**
     * Getter for `xlSharedStrings`.
     *
     * @since  v1.0
     * @throws ObjectDonoexException If SharedString object does not exists.
     * @return self
     */
    public function getXlSharedStrings() : XlSharedStrings
    {

        // Throw if missing.
        if ($this->xlSharedStrings === null) {
            throw (new ObjectDonoexException('SharedStringsOfXlsxFile'))->addObjectInfos($this);
        }

        return $this->xlSharedStrings;
    }

    /**
     * Getter for `xlWorkbook`.
     *
     * @since  v1.0
     * @throws ObjectDonoexException If Workbook object does not exists.
     * @return self
     */
    public function getXlWorkbook() : XlWorkbook
    {

        // Throw if missing.
        if ($this->xlSharedStrings === null) {
            throw (new ObjectDonoexException('WorkbookOfXlsxFile'))->addObjectInfos($this);
        }

        return $this->xlWorkbook;
    }

    /**
     * Getter for `xlWorksheet`.
     *
     * @since  v1.0
     * @return self
     */
    public function getXlWorksheets() : array
    {

        return $this->xlWorksheets;
    }

    /**
     * Getter for `xlTables`.
     *
     * @since  v1.0
     * @return array
     */
    public function getXlTables() : array
    {

        return $this->xlTables;
    }

    /**
     * Used by Sexceptions to introduce this object when it causes exceptions.
     *
     * @since  v1.0
     * @return array
     */
    public function getExceptionInfos() : array
    {

        return [
            'xlsxFileUri' => $this->xlsxFileUri,
        ];
    }

    /**
     * Unpacks ZIP into XML and RELS files.
     *
     * @since  v1.0
     * @throws MethodFopException If unpacking failed somehow.
     * @return self
     *
     * @phpcs:disable Generic.PHP.NoSilencedErrors
     */
    private function unpack() : self
    {

        // Create ZIP object.
        $zip = new ZipArchive();

        // Try to open file.
        $open    = @$zip->open($this->xlsxFileUri);
        $extract = @$zip->extractTo($this->unzipUri);
        $close   = @$zip->close();


        var_dump($this->unzipUri);
        $dh = opendir($this->unzipUri);
        var_dump($dh);
        while ($entry = readdir($dh)) {
            var_dump($entry);
        }

        var_dump($this->unzipUri . '/xl');
        $dh = opendir($this->unzipUri . '/xl');
        var_dump($dh);
        while ($entry = readdir($dh)) {
            var_dump($entry);
        }
        // glob($this->unzipUri . '/*.*');
        die;

        // Throw if needed.
        if (empty(min($open, $extract, $close)) === true) {
            throw (new MethodFopException('openExtractOrClosingZipArchiveFailed'))
                ->addWarning()->addInfo('xlsxFileUri', $this->xlsxFileUri);
        }

        // Get all files.
        $this->allFiles = $this->getFilesRecursively();

        return $this;
    }

    /**
     * Find all unpacekd files and convert them to XML Objects.
     *
     * @since  v1.0
     * @return self
     */
    private function readXmlFilesIntoObjects() : self
    {

        // Find workbook.xml in allFiles and read it in into XlWorkbook object.
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

        return $this;
    }

    /**
     * Creates final XLSx object to use.
     *
     * @since  v1.0
     * @throws ClassFopException When reading XML files somehow failed.
     * @return self
     */
    private function createXlsx() : self
    {

        // Lvd.
        $cellsReadByTables = [];

        // Add worksheets.
        foreach ($this->getXlWorksheets() as $sheetXml) {
            $this->xlsx->getBook()->addSheet($sheetXml->getName(), $sheetXml->getId());
        }

        // Add tables.
        foreach ($this->getXlTables() as $tableXml) {

            // Lvd.
            $sheetId = $tableXml->getXlWorksheet()->getId();
            $sheet   = $this->xlsx->getBook()->getSheet($sheetId);

            // Add table.
            $table = $sheet->addTable(
                $tableXml->getName(),
                $tableXml->getFirstRow(),
                $tableXml->getFirstCol(),
                $tableXml->getId(),
            );

            // Add table contents.
            $table->addColumns($tableXml->getColumns());
            $table->setData($tableXml->getData());

            // Save which cells has been read by this table.
            $cellsReadByTables[$sheet->getId()] = array_merge(
                ( $cellsReadByTables[$sheet->getId()] ?? [] ),
                $tableXml->getCellsRead()
            );
        }//end foreach

        // Read cells from sheets - but only those that were not read by tables already.
        // List of these is stored in $this->cellsReadByTables[for_sheet_id].
        foreach ($this->getXlWorksheets() as $sheetXml) {

            // Lvd.
            $sheet = $this->xlsx->getBook()->getSheetByName($sheetXml->getName());
            $dims  = $sheetXml->getDimensionRefAsArray();

            // Scan every row.
            for ($r = $dims['firstCell'][0]; $r <= $dims['lastCell'][0]; ++$r) {

                // Lvd.
                $wholeRowWasEmpty = true;

                // Scan every cell.
                for ($c = $dims['firstCell'][1]; $c <= $dims['lastCell'][1]; ++$c) {

                    // If this was already read - ignore it.
                    if (in_array([ $r, $c ], ( $cellsReadByTables[$sheet->getId()] ?? [] )) === true) {
                        continue;
                    }

                    // Otherwise get value of this cell.
                    $value = $sheetXml->getCellValue($r, $c);

                    if ($value === null) {
                        continue;
                    } else {
                        $wholeRowWasEmpty = false;
                    }

                    // Define value of cell inside XLSX object.
                    $sheet->getCell($r, $c)->setValue($value);
                }

                // Stop reading next rows - becuase this row was already empty in full.
                if ($wholeRowWasEmpty === true) {
                    break;
                }
            }//end for
        }//end foreach

        return $this;
    }

    /**
     * Scans directory with unpacked ZIP recursively to get list of all files.
     *
     * @param null|string $dir Optional directory path. If not given $this->unzipUri is used.
     *
     * @since  v1.0
     * @throws DirIsEmptyException If unpacked directory is empty.
     * @return array
     */
    private function getFilesRecursively(?string $dir = null) : array
    {

        // If no dir given take from instance.
        if (is_null($dir) === true) {
            $dir = $this->unzipUri;
        }

        // If no files are present - empty main dir after unpacking.
        if ($dir === $this->unzipUri && count(scandir($dir)) <= 2) {
            throw new DirIsEmptyException('tempDirWithUnpackedXlsx', $dir);
        }

        // Lvd.
        $sep     = '\\';
        $results = [];

        // Scan directory to find for filex.
        foreach (scandir($dir) as $name) {

            // Ignore.
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

    /**
     * Introduce XlSharedStrings (there is only one) to this XLSX.
     *
     * @param string $fileUri File URI of XML SharedStrings file unpacked from XLSX.
     *
     * @since  v1.0
     * @return self
     */
    private function setXlSharedStrings(string $fileUri) : self
    {

        // Save.
        $this->xlSharedStrings = new XlSharedStrings($fileUri, $this);

        return $this;
    }

    /**
     * Introduce XlWorkbook (there is only one) to this XLSX.
     *
     * @param string $fileUri File URI of XML Workbook file unpacked from XLSX.
     *
     * @since  v1.0
     * @return self
     */
    private function setXlWorkbook(string $fileUri) : self
    {

        // Save.
        $this->xlWorkbook = new XlWorkbook($fileUri, $this);

        return $this;
    }

    /**
     * Introduce new XlWorksheet to this XLSX.
     *
     * @param string $fileUri File URI of XML worksheet file unpacked from XLSX.
     *
     * @since  v1.0
     * @return self
     */
    private function addXlWorksheet(string $fileUri) : self
    {

        // Add new Worksheet.
        $this->xlWorksheets[] = new XlWorksheet($fileUri, $this);

        return $this;
    }

    /**
     * Introduce new XlTable to this XLSX.
     *
     * @param string $fileUri File URI of XML table files unpacked from XLSX.
     *
     * @since  v1.0
     * @return self
     */
    private function addXlTable(string $fileUri) : self
    {

        // Add new Table.
        $this->xlTables[] = new XlTable($fileUri, $this);

        return $this;
    }
}
