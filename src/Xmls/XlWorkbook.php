<?php declare(strict_types=1);

/*
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */

namespace Przeslijmi\XlsxPeasant\Xmls;

use Przeslijmi\XlsxPeasant\Xlsx;
use Przeslijmi\XlsxPeasant\Xml;

/**
 * XML nodes for `xl\workbook.xml`.
 */
class XlWorkbook extends Xml
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
            'workbook' => [
                '@xmlns'        => 'http://schemas.openxmlformats.org/spreadsheetml/2006/main',
                '@xmlns:r'      => 'http://schemas.openxmlformats.org/officeDocument/2006/relationships',
                '@xmlns:mc'     => 'http://schemas.openxmlformats.org/markup-compatibility/2006',
                '@mc:Ignorable' => 'x15 xr xr6 xr10 xr2',
                '@xmlns:x15'    => 'http://schemas.microsoft.com/office/spreadsheetml/2010/11/main',
                '@xmlns:xr'     => 'http://schemas.microsoft.com/office/spreadsheetml/2014/revision',
                '@xmlns:xr6'    => 'http://schemas.microsoft.com/office/spreadsheetml/2016/revision6',
                '@xmlns:xr10'   => 'http://schemas.microsoft.com/office/spreadsheetml/2016/revision10',
                '@xmlns:xr2'    => 'http://schemas.microsoft.com/office/spreadsheetml/2015/revision2',
                '@@'            => [
                    'fileVersion' => [
                        '@appName' => 'xl',
                        '@lastEdited' => '7',
                        '@lowestEdited' => '7',
                        '@rupBuild' => '21425',
                    ],
                    'workbookPr' => [
                        '@defaultThemeVersion' => '166925',
                    ],
                    'mc:AlternateContent' => [
                        '@xmlns:mc' => 'http://schemas.openxmlformats.org/markup-compatibility/2006',
                        '@@' => [
                            'mc:Choice' => [
                                '@Requires' => 'x15',
                                '@@' => [
                                    'x15ac:absPath' => [
                                        '@url' => '',
                                        '@xmlns:x15ac' => 'http://schemas.microsoft.com/office/spreadsheetml/2010/11/ac',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'bookViews' => [
                        '@@' => [
                            'workbookView' => [
                                '@xWindow'      => '-108',
                                '@yWindow'      => '-108',
                                '@windowWidth'  => '23256',
                                '@windowHeight' => '12576',
                                '@xr2:uid'      => '{1DA762AD-7C25-487E-83A6-EDAF663DE464}',
                            ],
                        ],
                    ],
                    'sheets' => [
                        '@@' => [
                            'sheet' => [
                            ],
                        ],
                    ],
                    'calcPr' => [
                        '@calcId' => '191029',
                    ],
                    'extLst' => [
                        '@@' => [
                            'ext' => [
                                '@uri' => '{140A7094-0E35-4892-8432-C4D2E57EDEB5}',
                                '@xmlns:x15' => 'http://schemas.microsoft.com/office/spreadsheetml/2010/11/main',
                                '@@' => [
                                    'x15:workbookPr' => [
                                        '@chartTrackingRefBase' => '1',
                                    ],
                                ],
                            ],
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

        $this->array['workbook']['@@']['mc:AlternateContent']['@@']['mc:Choice']['@@']['x15ac:absPath']['@url'] = $this->xlsx->getTargetUri(true);

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
        $sheets   = $this->xlsx->getBook()->getSheets();
        $sheetsAr = [];

        // Prepare info on every sheet.
        foreach ($sheets as $sheet) {
            $sheetsAr[] = [
                '@name'    => $sheet->getName(),
                '@sheetId' => $sheet->getId(),
                '@r:id'    => 'rId' . $sheet->getId(),
            ];
        }

        // Save.
        $this->array['workbook']['@@']['sheets']['@@']['sheet'] = $sheetsAr;

        return $this;
    }
}
