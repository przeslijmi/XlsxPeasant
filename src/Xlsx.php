<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant;

use Przeslijmi\XlsxPeasant\Exceptions\GenerationFailedException;
use Przeslijmi\XlsxPeasant\Exceptions\NoZipArchiveException;
use Przeslijmi\XlsxPeasant\Exceptions\TargetDirectoryDonoexException;
use Przeslijmi\XlsxPeasant\Exceptions\TargetFileAlrexException;
use Przeslijmi\XlsxPeasant\Exceptions\TargetFileDeletionFailedException;
use Przeslijmi\XlsxPeasant\Exceptions\TargetFileWrosynException;
use Przeslijmi\XlsxPeasant\Exceptions\UnknownDefaultSettingException;
use Przeslijmi\XlsxPeasant\Exceptions\WrotypeDefaultsException;
use Przeslijmi\XlsxPeasant\Items\Book;
use Przeslijmi\XlsxPeasant\Items\Collections\SharedStrings;
use Przeslijmi\XlsxPeasant\Items\Collections\Styles;
use Przeslijmi\XlsxPeasant\Items\Color;
use Przeslijmi\XlsxPeasant\Items\Fill;
use Przeslijmi\XlsxPeasant\Items\Font;
use Przeslijmi\XlsxPeasant\Items\Format;
use Przeslijmi\XlsxPeasant\Items\Style;
use Przeslijmi\XlsxPeasant\Xmls;
use Throwable;
use ZipArchive;

/**
 * XLSx sheets generator.
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
     * Which wrap text is going to be used when creating new Cell objects (eg. `LC`).
     *
     * @var boolean
     */
    private $useWrapText;

    /**
     * Which number fromat is going to be used when creating new Cell objects).
     *
     * @var Format
     */
    private $useFormat;

    /**
     * Array of registered formats for this XLSX.
     *
     * @var Format[]
     */
    private $registeredFormats = [
        0
    ];

    /**
     * Array with default values for this document.
     *
     * @var array
     */
    private $defaults = [
        'fontColor' => null,
        'fontSize'  => null,
        'fontName'  => null,
    ];

    /**
     * Where to file has to be generated.
     *
     * @var string
     */
    private $targetUri;

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
     * Setter for `targetUri`.
     *
     * @param string  $targetUri Uri to which file has to be generated.
     * @param boolean $overwrite Optional, false. Set to true to allow to overwrite file if exists.
     *
     * @since  v1.0
     * @throws TargetFileAlrexException          When targetUri exists but overwriting is forbidden.
     * @throws TargetFileWrosynException         When targetUri is written wrong (eg. is empty).
     * @throws TargetDirectoryDonoexException    When targetUri directory does not exists.
     * @throws TargetFileDeletionFailedException When overwriting failed (file is open?).
     * @return string
     *
     * @phpcs:disable Generic.PHP.NoSilencedErrors
     */
    private function setTargetUri(string $targetUri, bool $overwrite = false) : self
    {

        // Lvd.
        $targetUri = str_replace('\\', '/', $targetUri);

        // Test Uri.
        if (file_exists($targetUri) === true && $overwrite === false) {
            throw new TargetFileAlrexException($targetUri);
        }
        if (empty($targetUri) === true || is_dir($targetUri) === true) {
            throw new TargetFileWrosynException($targetUri);
        }

        // Check if directory in which creation have to be done exists.
        if (strrpos($targetUri, '/') !== false) {

            // Lvd.
            $directory = substr($targetUri, 0, strrpos($targetUri, '/')) . '/';

            // Throw.
            if (file_exists($directory) === false) {
                throw new TargetDirectoryDonoexException($directory, $targetUri);
            }
        }

        // Delete target file Uri if exists.
        if (file_exists($targetUri) === true) {

            // Throw if deletion is not possible.
            if (@touch($targetUri, time()) === false
                || ($fh = @fopen($targetUri, 'r+')) === false
                || @flock($fh, LOCK_EX|LOCK_NB) === false
            ) {
                throw new TargetFileDeletionFailedException();
            }

            unlink($targetUri);
        }

        $this->targetUri = $targetUri;

        return $this;
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
     * Getter for `targetUri`.
     *
     * @param boolean $onlyDir Optional, false. If set to true - only dir - not full target URI will be returned.
     *
     * @since  v1.0
     * @return string
     */
    public function getTargetUri(bool $onlyDir = false) : string
    {

        // Return only dir of file - not the whole path.
        if ($onlyDir === true) {
            return substr($this->targetUri, 0, (int) strrpos($this->targetUri, '\\')) . '\\';
        }

        return $this->targetUri;
    }

    /**
     * Generates XLSX file.
     *
     * @param string  $targetUri Uri to which file has to be generated.
     * @param boolean $overwrite Optional, false. Set to true to allow to overwrite file if exists.
     *
     * @since  v1.0
     * @throws NoZipArchiveException     When ZipArchive class does not exists.
     * @throws GenerationFailedException When somehow generation failed.
     * @throws GenerationFailedException When closing ZIP failed.
     * @return void
     *
     * @phpcs:disable Generic.PHP.NoSilencedErrors.Discouraged
     */
    public function generate(string $targetUri, bool $overwrite = false) : void
    {

        // Save target URI because XML will need it also.
        $this->setTargetUri($targetUri, $overwrite);

        // Throw.
        if (class_exists('ZipArchive') === false) {
            throw new NoZipArchiveException();
        }

        // Prepare all strings.
        $stringsToAdd = [
            'sheets'      => [],
            'sheets_rels' => [],
            'tables'      => [],
        ];

        // Add Worksheets.
        foreach ($this->getBook()->getSheets() as $sheet) {

            // Add Sheet.
            $stringsToAdd['sheets'][$sheet->getId()] = [
                'file' => 'xl/worksheets/sheet' . $sheet->getId() . '.xml',
                'text' => $sheet->getXml()->toXml(),
            ];

            // Add Tables for this Sheet.
            if ($sheet->hasTables() === true) {
                $stringsToAdd['sheets_rels'][$sheet->getId()] = [
                    'file' => 'xl/worksheets/_rels/sheet' . $sheet->getId() . '.xml.rels',
                    'text' => $sheet->getRelsXml()->toXml(),
                ];
            }

            // Add Tables.
            foreach ($this->getBook()->getTables() as $table) {
                $stringsToAdd['tables'][$table->getId()] = [
                    'file' => 'xl/tables/table' . $table->getId() . '.xml',
                    'text' => $table->getXml()->toXml(),
                ];
            }
        }//end foreach

        // Create ZIP.
        try {

            // Crate new ZipArchive.
            $zip = new ZipArchive();
            $zip->open($this->getTargetUri(), ZipArchive::CREATE);

            // Add all files in proper order.
            $zip->addFromString('_rels/.rels', $this->xmls->getRelsRels()->toXml());
            $zip->addFromString('docProps/app.xml', $this->xmls->getDocPropsApp()->toXml());
            $zip->addFromString('docProps/core.xml', $this->xmls->getDocPropsCore()->toXml());
            $zip->addFromString('xl/sharedStrings.xml', $this->xmls->getXlSharedStrings()->toXml());
            $zip->addFromString('xl/styles.xml', $this->xmls->getXlStyles()->toXml());

            // Add Tables.
            foreach ($stringsToAdd['tables'] as $add) {
                $zip->addFromString($add['file'], $add['text']);
            }

            // Add next.
            $zip->addFromString('xl/theme/theme1.xml', $this->xmls->getXlTheme()->toXml());
            $zip->addFromString('xl/workbook.xml', $this->xmls->getXlWorkbook()->toXml());

            // Add Sheets.
            foreach ($stringsToAdd['sheets'] as $add) {
                $zip->addFromString($add['file'], $add['text']);
            }
            foreach ($stringsToAdd['sheets_rels'] as $add) {
                $zip->addFromString($add['file'], $add['text']);
            }

            // Add next.
            $zip->addFromString('xl/_rels/workbook.xml.rels', $this->xmls->getXlRelsWorkbook()->toXml());
            $zip->addFromString('[Content_Types].xml', $this->xmls->getContentTypes()->toXml());

        } catch (Throwable $thr) {
            throw new GenerationFailedException($thr);
        }//end try

        // Close ZIP.
        if (@$zip->close() === false) {
            throw (new GenerationFailedException())->addWarning();
        }
    }

    /**
     * Setter for `useStyle`.
     *
     * @param null|Style $style Style to use in new Cells.
     *
     * @since  v1.0
     * @return self
     */
    public function useStyle(?Style $style) : self
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
    public function useFill(?Fill $fill) : self
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
    public function useFont(?Font $font) : self
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
    public function useAlign(?string $align) : self
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
     * Setter for `useWrapText`.
     *
     * @param boolean $wrapText WrapText to use in new Cells (eg. LC, RB).
     *
     * @since  v1.0
     * @return self
     */
    public function useWrapText(?bool $wrapText) : self
    {

        $this->useWrapText = $wrapText;

        return $this;
    }

    /**
     * Getter for `useWrapText`.
     *
     * @since  v1.0
     * @return null|boolean
     */
    public function getWrapTextToUse() : ?bool
    {

        return $this->useWrapText;
    }

    /**
     * Setter for `useFormat`.
     *
     * @param Format $format Number format to use in new Cells (eg. LC, RB).
     *
     * @since  v1.0
     * @return self
     */
    public function useFormat(?Format $format) : self
    {

        $this->useFormat = $format;

        return $this;
    }

    /**
     * Getter for `useFormat`.
     *
     * @since  v1.0
     * @return null|Format
     */
    public function getFormatToUse() : ?Format
    {

        return $this->useFormat;
    }

    /**
     * Return id of Format unique for given XLSX - while this format can be also used in other XLSX.
     *
     * @param Format $format Format Item object.
     *
     * @since  v1.0
     * @return integer
     */
    public function registerFormatsId(Format $format) : int
    {

        // Get class id - to find for it in rgisteredFormats array.
        $splId = spl_object_id($format);

        // If it not exists - add it.
        if (( $id = array_search($splId, $this->registeredFormats) ) === false) {

            // Add.
            $this->registeredFormats[] = $splId;

            // Calc new id (it as added at the end so if array has 5 elements after adding
            // new id is [0, 1, 2, 3, 4] ... 4; ie. `5 - 1`).
            $id = ( count($this->registeredFormats) - 1 );
        }

        return $id;
    }

    /**
     * Setter for default value.
     *
     * ## Usage example
     * ```
     * $xlsx->setDefaults('fontName', 'Times New Roman');
     * $xlsx->setDefaults('fontSize', 15);
     * $xlsx->setDefaults('fontColor', Color::factory('white'));
     * ```
     *
     * @param string                            $name  Setting name.
     * @param integer|float|boolean|string|null $value Setting value.
     *
     * @since  v1.0
     * @throws WrotypeDefaultsException       When trying to set value of defaults with wrong type..
     * @throws UnknownDefaultSettingException When trying to set non-existing default.
     * @return self
     */
    public function setDefault(string $name, $value) : self
    {

        // Check if this settings exists.
        if (in_array($name, array_keys($this->defaults)) === false) {
            throw new UnknownDefaultSettingException($name, array_keys($this->defaults));
        }

        // Check if this settings primitive type is proper.
        if ($name === 'fontSize' && is_int($value) === false) {
            throw new WrotypeDefaultsException($name, 'int', gettype($value));
        } elseif ($name === 'fontColor' && is_object($value) === false) {
            throw new WrotypeDefaultsException($name, 'object Color', gettype($value));
        } elseif ($name === 'fontName' && is_string($value) === false) {
            throw new WrotypeDefaultsException($name, 'string', gettype($value));
        }

        // Check if this settings object type is proper.
        if ($name === 'fontColor' && is_a($value, 'Przeslijmi\XlsxPeasant\Items\Color') === false) {
            throw new WrotypeDefaultsException($name, 'Przeslijmi\XlsxPeasant\Items\Color', get_class($value));
        }

        $this->defaults[$name] = $value;

        return $this;
    }

    /**
     * Getter for default value.
     *
     * @param string $name Setting name.
     *
     * @since  v1.0
     * @throws UnknownDefaultSettingException When trying to get non-existing default.
     * @return mixed
     */
    public function getDefault(string $name)
    {

        // Check.
        if (in_array($name, array_keys($this->defaults)) === false) {
            throw new UnknownDefaultSettingException($name, array_keys($this->defaults));
        }

        return $this->defaults[$name];
    }

    /**
     * Nullifies all `use*`. New cells will be as defaults are given.
     *
     * @since  v1.0
     * @return self
     */
    public function useDefaults() : self
    {

        // Reset to defaults.
        $this->useStyle(null);
        $this->useFill(null);
        $this->useFont(null);
        $this->useAlign(null);
        $this->useWrapText(null);
        $this->useFormat(null);

        return $this;
    }

    /**
     * Getter for default font.
     *
     * @since  v1.0
     * @return Font
     */
    public function getDefaultFont() : Font
    {

        $font = new Font();
        $font->setName($this->getDefault('fontName'));
        $font->setSize($this->getDefault('fontSize'));
        $font->setColor($this->getDefault('fontColor'));

        return $font;
    }
}
