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
     * Uri of XLSX file to read.
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
     * How many empty rows there has to be to stop reading XLSX file (default = 100).
     *
     * @var integer
     */
    private $stopReadingOnEmptyRows = 100;

    /**
     * Constructor.
     *
     * @param string $xlsxFileUri Uri of XLSX file.
     *
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

            // Find spare dir.
            do {

                // Try this.
                $unzipUri = 'examples/.temp/unpack_' . rand(1000000, 9999999);

                // Maybe this is ok.
                if (file_exists($unzipUri) === false) {
                    $this->unzipUri = $unzipUri;
                }

            } while (empty($this->unzipUri) === true);

        } catch (Throwable $thr) {
            throw new ClassFopException('creatingXlsxReader', $thr);
        }//end try
    }

    /**
     * Setter for how many empty rows there has to be to stop reading XLSX file (default = 100).
     *
     * @param integer $stopReadingOnEmptyRows How many empty rows there has to be to stop reading XLSX file.
     *
     * @return self
     */
    public function setStopReadingOnEmptyRows(int $stopReadingOnEmptyRows) : self
    {

        // Save.
        $this->stopReadingOnEmptyRows = $stopReadingOnEmptyRows;

        return $this;
    }

    /**
     * Getter for how many empty rows there has to be to stop reading XLSX file (default = 100).
     *
     * @return integer
     */
    public function getStopReadingOnEmptyRows() : int
    {

        return $this->stopReadingOnEmptyRows;
    }

    /**
     * Main working function - reads file into XLSX object.
     *
     * @param null|array $limitToTables Optional, null. You can limit to read in only given tables by name (or none).
     * @param null|array $limitToSheets Optional, null. You can limit to read in only given sheets by name (or none).
     *
     * @throws ClassFopException If any stage of creating Xlsx object from Xlsx file fails.
     * @return Xlsx
     */
    public function readIn(?array $limitToTables = null, ?array $limitToSheets = null) : Xlsx
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

        // Create final XLSX object.
        try {

            // Create.
            $this->xlsx = new Xlsx();

            // Fill up.
            $this->createXlsx($limitToTables, $limitToSheets);

        } catch (Throwable $thr) {
            throw (new ClassFopException('creatingXlsxFromReader', $thr))
                ->addInfo('xlsxFileUri', $this->xlsxFileUri);
        }

        return $this->xlsx;
    }

    /**
     * Getter for whole XLSx.
     *
     * @return Xlsx
     */
    public function getXlsx() : Xlsx
    {

        return $this->xlsx;
    }

    /**
     * Getter for `xlSharedStrings`.
     *
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
     * @return self
     */
    public function getXlWorksheets() : array
    {

        return $this->xlWorksheets;
    }

    /**
     * Getter for `xlTables`.
     *
     * @return array
     */
    public function getXlTables() : array
    {

        return $this->xlTables;
    }

    /**
     * Used by Sexceptions to introduce this object when it causes exceptions.
     *
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
     * Creates final XLSX object to use.
     *
     * @param null|array $limitToTables Optional, null. You can limit to read in only given tables by name (or none).
     * @param null|array $limitToSheets Optional, null. You can limit to read in only given sheets by name (or none).
     *
     * @throws ClassFopException When reading XML files somehow failed.
     * @return self
     */
    private function createXlsx(?array $limitToTables = null, ?array $limitToSheets = null) : self
    {

        // Lvd.
        $cellsReadByTables = [];

        // Add worksheets.
        foreach ($this->getXlWorksheets() as $sheetXml) {
            $this->xlsx->getBook()->addSheet($sheetXml->getName(), $sheetXml->getId());
        }

        // Add tables.
        foreach ($this->getXlTables() as $tableXml) {

            // Check if this table is not going to be ignored - ignore if needed.
            if ($limitToTables !== null && in_array($tableXml->getName(), $limitToTables) === false) {
                continue;
            }

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

            // Check if this table is not going to be ignored - ignore if needed.
            if ($limitToSheets !== null && in_array($sheetXml->getName(), $limitToSheets) === false) {
                continue;
            }

            // Lvd.
            $sheet            = $this->xlsx->getBook()->getSheetByName($sheetXml->getName());
            $dims             = $sheetXml->getDimensionRefAsArray();
            $emptyRowsCounter = 0;

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

                // Enlarge or reset empty rows counter.
                if ($wholeRowWasEmpty === true) {
                    ++$emptyRowsCounter;
                } else {
                    $emptyRowsCounter = 0;
                }

                // If limit is set and counter is beyond limit - stop.
                if ($this->stopReadingOnEmptyRows > -1 && $emptyRowsCounter >= $this->stopReadingOnEmptyRows) {
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
     * @throws DirIsEmptyException If unpacked directory is empty.
     * @return array
     */
    private function getFilesRecursively(?string $dir = null) : array
    {

        // Lvd.
        $sep     = '/';
        $results = [];

        // If no dir given take from instance.
        if (is_null($dir) === true) {
            $dir = $this->unzipUri;
        }

        // Cut off last sep.
        $dir = str_replace('\\', '/', $dir);
        $dir = rtrim($dir, '/');

        // If no files are present - empty main dir after unpacking.
        if ($dir === $this->unzipUri && count(scandir($dir)) <= 2) {
            throw new DirIsEmptyException('tempDirWithUnpackedXlsx', $dir);
        }

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
     * @return self
     */
    private function addXlTable(string $fileUri) : self
    {

        // Add new Table.
        $this->xlTables[] = new XlTable($fileUri, $this);

        return $this;
    }
}
