<?php declare(strict_types=1);

/*
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */

namespace Przeslijmi\XlsxPeasant\Xmls;

use Przeslijmi\XlsxPeasant\Xml;
use Przeslijmi\XlsxPeasant\Items\Sheet;
use Przeslijmi\XlsxPeasant\Items\Cell;
use Przeslijmi\XlsxPeasant\Items\Row;

/**
 * XML nodes for `xl\worksheets\sheet*.xml`.
 */
class XlWorksheet extends Xml
{

    /**
     * Parent Sheet.
     *
     * @var Sheet
     */
    private $sheet;

    /**
     * Constructor.
     *
     * @param Sheet $sheet Sheet to import to this XML.
     */
    public function __construct(Sheet $sheet)
    {

        // Save parent.
        $this->sheet = $sheet;

        // Define nodes.
        $this->array = [
            'worksheet' => [
                '@xmlns'        => 'http://schemas.openxmlformats.org/spreadsheetml/2006/main',
                '@xmlns:r'      => 'http://schemas.openxmlformats.org/officeDocument/2006/relationships',
                '@xmlns:mc'     => 'http://schemas.openxmlformats.org/markup-compatibility/2006',
                '@mc:Ignorable' => 'x14ac xr xr2 xr3',
                '@xmlns:x14ac'  => 'http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac',
                '@xmlns:xr'     => 'http://schemas.microsoft.com/office/spreadsheetml/2014/revision',
                '@xmlns:xr2'    => 'http://schemas.microsoft.com/office/spreadsheetml/2015/revision2',
                '@xmlns:xr3'    => 'http://schemas.microsoft.com/office/spreadsheetml/2016/revision3',
                '@xr:uid'       => '{1F9F6C13-4E63-4813-B769-C4C69F609E0F}',
                '@@' => [
                    'dimension' => [
                        '@ref' => 'A1',
                    ],
                    'sheetViews' => [
                        '@@' => [
                            'sheetView' => [
                                '@workbookViewId' => '0',
                            ],
                        ],
                    ],
                    'sheetFormatPr' => [
                        '@defaultRowHeight' => '14.4',
                        '@x14ac:dyDescent'  => '0.3',
                    ],
                    'cols' => [
                        '@@' => [
                            'col' => [
                            ]
                        ],
                    ],
                    'sheetData' => [
                        '@@' => null,
                    ],
                    'mergeCells' => [
                        '@@' => null,
                    ],
                    'conditionalFormatting' => [
                        '@@' => null,
                    ],
                    'pageMargins' => [
                        '@left'   => '0.7',
                        '@right'  => '0.7',
                        '@top'    => '0.75',
                        '@bottom' => '0.75',
                        '@header' => '0.3',
                        '@footer' => '0.3',
                    ],
                    'tableParts' => [
                        '@@' => null,
                    ],
                    'extLst' => [
                        '@@' => null,
                    ],
                ],
            ],
        ];

        $this->setConfigs(Xml::NO_INDENTATION | Xml::NO_NEW_LINES | Xml::NO_SPACE_ON_SHORTTAGS | Xml::NO_VALIDATION_NODE_NAME | Xml::NO_VALIDATION_ATTR_VALUE | Xml::NO_VALIDATION_ATTR_NAME);
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

        $this->prepCells();
        $this->prepMerges();
        $this->prepTables();
        $this->prepCols();
        $this->prepConditionalFormats();

        $this->array['worksheet']['@xr:uid']                 = $this->sheet->getUuid();
        $this->array['worksheet']['@@']['dimension']['@ref'] = $this->sheet->getDimensionRef();

        return $this;
    }

    /**
     * Preparation of `sheetData` node.
     *
     * @return self
     */
    private function prepCells() : self
    {

        // Lvd.
        $rowsAr = [];

        // Foreach Row.
        foreach ($this->sheet->getCells() as $row => $colsOrRow) {

            // Lvd.
            $colsXml = '';

            if (is_array($colsOrRow) === true) {

                // Lvd.
                $cols   = $colsOrRow;
                $minCol = array_keys($cols)[0];
                $maxCol = array_reverse(array_keys($cols))[0];

                // Foreach Col in Row.
                foreach ($cols as $col => $cell) {
                    $colsXml .= $this->prepOneCell($cell);
                }
            } else {

                // Lvd.
                $rowObj  = $colsOrRow;
                $minCol  = 1;
                $maxCol  = count($rowObj->getData());
                $colsXml = $this->prepOneRow($rowObj);
            }

            // Prepare row.
            $rowAr = [
                '@r'               => $row,
                '@spans'           => $minCol . ':' . $maxCol,
                '@x14ac:dyDescent' => '0.3',
                '@@@'              => $colsXml,
            ];

            // Check height.
            if ($this->sheet->getRowHeight($row) !== null) {
                $rowAr['@customHeight'] = '1';
                $rowAr['@ht']           = $this->sheet->getRowHeight($row);
            }

            // Add row.
            $rowsAr[] = $rowAr;
        }//end foreach

        // Save.
        if (empty($rowsAr) === false) {
            $this->array['worksheet']['@@']['sheetData']['@@']['row'] = $rowsAr;
        }

        return $this;
    }

    /**
     * Preparation of one Cell in `sheetData` node.
     *
     * @param Cell $cell Cell to prepare.
     *
     * @return string
     */
    private function prepOneCell(Cell $cell) : string
    {

        // Lvd.
        $result = '';

        // Default always.
        $arr = [
            '@r' => $cell->getColRef() . $cell->getRow(),
        ];

        // If this Cell has style - add it.
        if ($cell->hasStyle() === true) {
            $arr['@s'] = $cell->getStyle()->getId();
        } else {
            $arr['@s'] = 1;
        }

        // If this is not merged - add value.
        if ($cell->isMerged() === false) {

            if ($cell->getValueType() === 'string' || $cell->getValueType() === 'array') {
                $arr['@t'] = 's';
                $arr['@@'] = [
                    'v' => $cell->getSharedStringsId(),
                ];
            } else {
                $arr['@@'] = [
                    'v' => $cell->getNumericValue(),
                ];
            }

        }

        // If this is merged - add 0 style.
        if ($cell->isMerged() === true) {
            $arr['@s'] = '0';
        }

        // Compose result as direct XML contents.
        if (isset($arr['@t']) === true) {
            $result = '<c r="' . $arr['@r'] . '" s="' . $arr['@s'] . '" t="s"><v>' . $arr['@@']['v'] . '</v></c>';
        } elseif (isset($arr['@@']) === true) {
            $result = '<c r="' . $arr['@r'] . '" s="' . $arr['@s'] . '"><v>' . $arr['@@']['v'] . '</v></c>';
        } else {
            $result = '<c r="' . $arr['@r'] . '" s="' . $arr['@s'] . '"/>';
        }

        return $result;
    }

    private function prepOneRow(Row $row) : string
    {

        // Lvd.
        $result = '';

        foreach ($row->getData() as $col) {

            if ($col['sharedStringsId'] !== null) {
                $result .= '<c r="' . $col['colRef'] . $row->getRowId() . '" s="' . $col['style'] . '" t="s"><v>' . $col['sharedStringsId'] . '</v></c>';
            } else {
                $result .= '<c r="' . $col['colRef'] . $row->getRowId() . '" s="' . $col['style'] . '"><v>' . $col['value'] . '</v></c>';
            }
        }

        return $result;
    }

    /**
     * Prepare 'mergeCells' node.
     *
     * @return self
     */
    private function prepMerges() : self
    {

        // Lvd.
        $mergesAr = [];

        // Find all merges going through all Cells.
        foreach ($this->sheet->getCells() as $row => $cols) {

            if (is_array($cols) === false) {
                continue;
            }
            foreach ($cols as $col => $cell) {

                // If this is not merging Cell - don't go further.
                if ($cell->isMerging() === false) {
                    continue;
                }

                // Add to index.
                $mergesAr[] = [
                    '@ref' => $cell->getMergeRef(),
                ];
            }
        }

        // If there are merges - list them.
        if (count($mergesAr) > 0) {
            $this->array['worksheet']['@@']['mergeCells']                    = [];
            $this->array['worksheet']['@@']['mergeCells']['@count']          = count($mergesAr);
            $this->array['worksheet']['@@']['mergeCells']['@@']['mergeCell'] = $mergesAr;
        } else {
            unset($this->array['worksheet']['@@']['mergeCells']);
        }

        return $this;
    }

    /**
     * Preparation of `tableParts` node.
     *
     * @return self
     */
    private function prepTables() : self
    {

        // Lvd.
        $tablesAr = [];

        // Find all Tables.
        foreach ($this->sheet->getTables() as $table) {
            $tablesAr[] = [
                '@r:id' => 'rId' . $table->getId(),
            ];
        }

        // If there are merges - list them.
        if (count($tablesAr) > 0) {
            $this->array['worksheet']['@@']['tableParts']                    = [];
            $this->array['worksheet']['@@']['tableParts']['@count']          = count($tablesAr);
            $this->array['worksheet']['@@']['tableParts']['@@']['tablePart'] = $tablesAr;
        } else {
            unset($this->array['worksheet']['@@']['tableParts']);
        }

        return $this;
    }

    /**
     * Preparation of `cols` node.
     *
     * @return self
     */
    private function prepCols() : self
    {

        // Lvd.
        $colsAr = [];

        // Find all Tables.
        foreach ($this->sheet->getColsWidth() as $colId => $width) {
            $colsAr[] = [
                '@customWidth' => '1',
                '@max'         => $colId,
                '@min'         => $colId,
                '@width'       => $width,
            ];
        }

        // If there are merges - list them.
        if (count($colsAr) > 0) {
            $this->array['worksheet']['@@']['cols']              = [];
            $this->array['worksheet']['@@']['cols']['@@']['col'] = $colsAr;
        } else {
            unset($this->array['worksheet']['@@']['cols']);
        }

        return $this;
    }

    /**
     * Preparation of `conditionalFormatting` and `extLst` nodes - or deleting them.
     *
     * @return self
     */
    private function prepConditionalFormats() : self
    {

        // Found conditionalFormats.
        $found    = [];
        $cfXml    = [];
        $cfExtLst = [];

        // Find all conditional formatting in all Cells.
        foreach ($this->sheet->getCells() as $row => $cols) {

            if (is_array($cols) === false) {
                continue;
            }

            foreach ($cols as $col => $cell) {

                // If this is not merging Cell - don't go further.
                if ($cell->hasStyle() === false) {
                    continue;
                }

                // Lvd.
                if ($cell->getStyle()->hasConditionalFormat() === false) {
                    continue;
                }

                $format = $cell->getStyle()->getConditionalFormat();
                $uuid   = $format->getUuid();

                if (isset($found[$uuid]) === false) {
                    $found[$uuid] = [
                        'obj'   => $format,
                        'cells' => [],
                    ];
                }

                $found[$uuid]['cells'][] = $cell->getRef();
            }//end foreach
        }//end foreach

        // Shortcut.
        if (count($found) === 0) {
            unset($this->array['worksheet']['@@']['conditionalFormatting']);
            unset($this->array['worksheet']['@@']['extLst']);

            return $this;
        }

        // Convert them to XML.
        foreach ($found as $uuid => $conditionalFormat) {

            $cfXml = [
                '@sqref' => implode(' ', $conditionalFormat['cells']),
                '@@' => [
                    'cfRule' => [
                        '@priority' => '1',
                        '@type' => 'dataBar',
                        '@@' => [
                            'dataBar' => [
                                '@@' => [
                                    'cfvo' => [
                                        [
                                            '@type' => 'min',
                                        ],
                                        [
                                            '@type' => 'max',
                                        ],
                                    ],
                                    'color' => [
                                        [
                                            '@rgb' => 'FF63C384',
                                        ]
                                    ]
                                ]
                            ],
                            'extLst' => [
                                '@@' => [
                                    'ext' => [
                                        '@uri' => '{B025F937-C7B1-47D3-B67F-A62EFF666E3E}',
                                        '@xmlns:x14' => 'http://schemas.microsoft.com/office/spreadsheetml/2009/9/main',
                                        '@@' => [
                                            'x14:id' => '{1D1627CC-5FDE-48EA-B398-F01261DC7DAA}',
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            $cfExtLst[] = [
                '@xmlns:xm' => 'http://schemas.microsoft.com/office/excel/2006/main',
                '@@' => [
                    'x14:cfRule' => [
                        '@id' => '{1D1627CC-5FDE-48EA-B398-F01261DC7DAA}',
                        '@type' => 'dataBar',
                        '@@' => [
                            'x14:dataBar' => [
                                '@border' => '1',
                                '@maxLength' => '100',
                                '@minLength' => '0',
                                '@negativeBarBorderColorSameAsPositive' => '0',
                                '@@' => [
                                    'x14:cfvo' => [
                                        [
                                            '@type' => 'autoMin'
                                        ],
                                        [
                                            '@type' => 'autoMax'
                                        ]
                                    ],
                                    'x14:borderColor' => [
                                        [
                                            '@rgb' => 'FF63C384'
                                        ]
                                    ],
                                    'x14:negativeFillColor' => [
                                        [
                                            '@rgb' => 'FFFF0000'
                                        ]
                                    ],
                                    'x14:negativeBorderColor' => [
                                        [
                                            '@rgb' => 'FFFF0000'
                                        ]
                                    ],
                                    'x14:axisColor' => [
                                        [
                                            '@rgb' => 'FF000000'
                                        ]
                                    ],
                                ]
                            ]
                        ]
                    ],
                    'xm:sqref' => implode(' ', $conditionalFormat['cells']),
                ]
            ];
        }//end foreach

        // Finally add `extLst` to XML structure.
        $this->array['worksheet']['@@']['extLst'] = [
            '@@' => [
                'ext' => [
                    '@uri' => '{78C0D931-6437-407d-A8EE-F0AAD7539E65}',
                    '@xmlns:x14' => 'http://schemas.microsoft.com/office/spreadsheetml/2009/9/main',
                    '@@' => [
                        'x14:conditionalFormattings' => [
                            '@@' => [
                                'x14:conditionalFormatting' => $cfExtLst,
                            ]
                        ]
                    ]
                ]
            ]
        ];

        // Finally add `conditionalFormatting` to XML structure.
        $this->array['worksheet']['@@']['conditionalFormatting'] = $cfXml;

        return $this;
    }
}
