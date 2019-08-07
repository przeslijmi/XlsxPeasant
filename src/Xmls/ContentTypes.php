<?php declare(strict_types=1);

/*
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */

namespace Przeslijmi\XlsxPeasant\Xmls;

use Przeslijmi\XlsxPeasant\Xml;
use Przeslijmi\XlsxPeasant\Xlsx;

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

        // Add workbook.
        $this->array['Types']['@@']['Override'][] = [
            '@PartName'    => '/xl/workbook.xml',
            '@ContentType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml',
        ];

        // Add Sheets.
        foreach ($this->xlsx->getBook()->getSheets() as $sheet) {
            $this->array['Types']['@@']['Override'][] = [
                '@PartName'    => '/xl/worksheets/sheet' . $sheet->getId() . '.xml',
                '@ContentType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml',
            ];
        }

        // Add theme, styles and shared strings.
        $this->array['Types']['@@']['Override'][] = [
            '@PartName'    => '/xl/theme/theme1.xml',
            '@ContentType' => 'application/vnd.openxmlformats-officedocument.theme+xml',
        ];
        $this->array['Types']['@@']['Override'][] = [
            '@PartName'    => '/xl/styles.xml',
            '@ContentType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml',
        ];
        $this->array['Types']['@@']['Override'][] = [
            '@PartName'    => '/xl/sharedStrings.xml',
            '@ContentType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml',
        ];

        // Add Tables.
        foreach ($this->xlsx->getBook()->getTables() as $table) {
            $this->array['Types']['@@']['Override'][] = [
                '@PartName'    => '/xl/tables/table' . $table->getId() . '.xml',
                '@ContentType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.table+xml',
            ];
        }

        // Add core and app.
        $this->array['Types']['@@']['Override'][] = [
            '@PartName'    => '/docProps/core.xml',
            '@ContentType' => 'application/vnd.openxmlformats-package.core-properties+xml',
        ];
        $this->array['Types']['@@']['Override'][] = [
            '@PartName'    => '/docProps/app.xml',
            '@ContentType' => 'application/vnd.openxmlformats-officedocument.extended-properties+xml',
        ];

        return $this;
    }
}
