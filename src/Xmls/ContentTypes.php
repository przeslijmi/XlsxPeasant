<?php declare(strict_types=1);

/*
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */

namespace Przeslijmi\XlsxGenerator\Xmls;

use Przeslijmi\XlsxGenerator\Xml;
use Przeslijmi\XlsxGenerator\Xlsx;

/**
 * XML nodes for `[ContentTypes].xml`.
 */
class ContentTypes extends Xml
{

    /**
     * Parent XLSx file.
     *
     * @var Xlsx
     */
    private $xlsx;

    /**
     * Constructor.
     *
     * @param Xlsx $xlsx Xlsx to import to this XML.
     *
     * @since v1.0
     */
    public function __construct(Xlsx $xlsx)
    {

        // Save parent.
        $this->xlsx = $xlsx;

        // Define nodes.
        $this->array = [
            'Types' => [
                '@xmlns' => 'http://schemas.openxmlformats.org/package/2006/content-types',
                '@@'     => [
                    'Default' => [
                        [
                            '@Extension'   => 'rels',
                            '@ContentType' => 'application/vnd.openxmlformats-package.relationships+xml',
                        ],
                        [
                            '@Extension'   => 'xml',
                            '@ContentType' => 'application/xml',
                        ],
                    ],
                    'Override' => [
                        [
                            '@PartName'    => '/xl/workbook.xml',
                            '@ContentType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml',
                        ],
                        [
                            '@PartName'    => '/xl/worksheets/sheet1.xml',
                            '@ContentType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml',
                        ],
                        [
                            '@PartName'    => '/xl/theme/theme1.xml',
                            '@ContentType' => 'application/vnd.openxmlformats-officedocument.theme+xml',
                        ],
                        [
                            '@PartName'    => '/xl/styles.xml',
                            '@ContentType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml',
                        ],
                        [
                            '@PartName'    => '/xl/sharedStrings.xml',
                            '@ContentType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml',
                        ],
                        [
                            '@PartName'    => '/docProps/core.xml',
                            '@ContentType' => 'application/vnd.openxmlformats-package.core-properties+xml',
                        ],
                        [
                            '@PartName'    => '/docProps/app.xml',
                            '@ContentType' => 'application/vnd.openxmlformats-officedocument.extended-properties+xml',
                        ],
                    ],
                ],
            ],
        ];

        $this->setConfigs(Xml::NO_INDENTATION | Xml::NO_NEW_LINES | Xml::NO_SPACE_ON_SHORTTAGS);
        $this->setHeader('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>');

        parent::__construct();
    }

    /**
     * Preparation method to update `$this->array` according to current values.
     *
     * @return self
     */
    public function prep() : self
    {

        $this->prepSheets();

        return $this;
    }

    /**
     * Preparation of `sheet` node.
     *
     * @since  v1.0
     * @return self
     */
    private function prepSheets() : self
    {

        // Lvd.
        $sheetCt = 'application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml';

        // Delete all types with `$sheetCt` (sheets).
        foreach ($this->array['Types']['@@']['Override'] as $id => $element) {
            if ($element['@ContentType'] === $sheetCt) {
                unset($this->array['Types']['@@']['Override'][$id]);
            }
        }

        // Add sheets.
        foreach ($this->xlsx->getBook()->getSheets() as $sheet) {
            $this->array['Types']['@@']['Override'][] = [
                '@PartName'    => '/xl/worksheets/sheet' . $sheet->getId() . '.xml',
                '@ContentType' => $sheetCt,
            ];
        }

        return $this;
    }
}
