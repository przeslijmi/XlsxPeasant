<?php declare(strict_types=1);

/*
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */

namespace Przeslijmi\XlsxPeasant\Xmls;

use Przeslijmi\XlsxPeasant\Items\Cell;
use Przeslijmi\XlsxPeasant\Items\Color;
use Przeslijmi\XlsxPeasant\Xlsx;
use Przeslijmi\XlsxPeasant\Xml;
use Przeslijmi\XlsxPeasant\Xmls\Common\FontXml;

/**
 * XML nodes for `xl\styles.xml`.
 */
class XlStyles extends Xml
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
            'styleSheet' => [
                '@xmlns'        => 'http://schemas.openxmlformats.org/spreadsheetml/2006/main',
                '@xmlns:mc'     => 'http://schemas.openxmlformats.org/markup-compatibility/2006',
                '@mc:Ignorable' => 'x14ac x16r2 xr',
                '@xmlns:x14ac'  => 'http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac',
                '@xmlns:x16r2'  => 'http://schemas.microsoft.com/office/spreadsheetml/2015/02/main',
                '@xmlns:xr'     => 'http://schemas.microsoft.com/office/spreadsheetml/2014/revision',
                '@@' => [
                    'numFmts' => [
                        '@@' => null,
                    ],
                    'fonts' => [
                        '@count' => '1',
                        '@x14ac:knownFonts' => '1',
                        '@@' => [
                            'font' => [
                            ],
                        ],
                    ],
                    'fills' => [
                        '@count' => '2',
                        '@@' => [
                            'fill' => [
                                [
                                    '@@' => [
                                        'patternFill' => [
                                            '@patternType' => 'none',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'borders' => [
                        '@count' => '1',
                        '@@' => [
                            'border' => [
                                [
                                    '@@' => [
                                        'left'     => null,
                                        'right'    => null,
                                        'top'      => null,
                                        'bottom'   => null,
                                        'diagonal' => null,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'cellStyleXfs' => [
                        '@count' => '1',
                        '@@' => [
                            'xf' => [
                                '@numFmtId' => '0',
                                '@fontId'   => '0',
                                '@fillId'   => '0',
                                '@borderId' => '0',
                            ],
                        ],
                    ],
                    'cellXfs' => [
                        '@count' => '1',
                        '@@' => [
                            'xf' => [
                                [
                                    '@numFmtId' => '0',
                                    '@fontId'   => '0',
                                    '@fillId'   => '0',
                                    '@borderId' => '0',
                                    '@xfId'     => '0',
                                ],
                            ],
                        ],
                    ],
                    'cellStyles' => [
                        '@count' => '1',
                        '@@' => [
                            'cellStyle' => [
                                '@name'      => 'Normalny',
                                '@xfId'      => '0',
                                '@builtinId' => '0',
                            ],
                        ],
                    ],
                    'dxfs' => [
                        '@count' => '0',
                    ],
                    'tableStyles' => [
                        '@count'             => '0',
                        '@defaultTableStyle' => 'TableStyleMedium2',
                        '@defaultPivotStyle' => 'PivotStyleLight16',
                    ],
                    'extLst' => [
                        '@@' => [
                            'ext' => [
                                [
                                    '@uri' => '{EB79DEF2-80B8-43e5-95BD-54CBDDF9020C}',
                                    '@xmlns:x14' => 'http://schemas.microsoft.com/office/spreadsheetml/2009/9/main',
                                    '@@' => [
                                        'x14:slicerStyles' => [
                                            '@defaultSlicerStyle' => 'SlicerStyleLight1',
                                        ],
                                    ],
                                ],
                                [
                                    '@uri' => '{9260A510-F301-46a8-8635-F512D64BE5F5}',
                                    '@xmlns:x15' => 'http://schemas.microsoft.com/office/spreadsheetml/2010/11/main',
                                    '@@' => [
                                        'x15:timelineStyles' => [
                                            '@defaultTimelineStyle' => 'TimeSlicerStyleLight1',
                                        ],
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

        $this->prepStyles();

        return $this;
    }

    /**
     * Preparation of `fonts`, `fills`, `cellXfs` node.
     *
     * @since  v1.0
     * @return self
     */
    private function prepStyles() : self
    {

        // Lvd.
        $styles    = $this->xlsx->getStyles()->getIndex();
        $fontsAr   = [];
        $fillsAr   = [];
        $formatsAr = [];
        $cellXfsAr = [];

        // Define standard array's contents.
        $fontsAr[]   = [
            '@@' => [
                'sz' => [
                    '@val' => $this->xlsx->getDefault('fontSize'),
                ],
                'color' => [
                    '@rgb' => $this->xlsx->getDefault('fontColor')->get(),
                ],
                'name' => [
                    '@val' => $this->xlsx->getDefault('fontName'),
                ],
                'family' => [
                    '@val' => '2',
                ],
                'charset' => [
                    '@val' => '238',
                ],
                'scheme' => [
                    '@val' => 'minor',
                ],
            ],
        ];
        $formatsAr[] = [
            '@formatCode' => '#,##0\ &quot;zł&quot;;\-#,##0\ &quot;zł&quot;',
            '@numFmtId'   => '5',
        ];
        $fontsAr[]   = [
            '@@' => [
                'sz' => [
                    '@val' => $this->xlsx->getDefault('fontSize'),
                ],
                'color' => [
                    '@rgb' => $this->xlsx->getDefault('fontColor')->get(),
                ],
                'name' => [
                    '@val' => $this->xlsx->getDefault('fontName'),
                ],
                'family' => [
                    '@val' => '2',
                ],
                'charset' => [
                    '@val' => '238',
                ],
            ],
        ];
        $fillsAr[]   = [
            '@@' => [
                'patternFill' => [
                    '@patternType' => 'none',
                ],
            ],
        ];
        $fillsAr[]   = [
            '@@' => [
                'patternFill' => [
                    '@patternType' => 'gray125',
                ],
            ],
        ];
        $cellXfsAr[] = [
            '@borderId' => 0,
            '@fillId'   => 0,
            '@fontId'   => 0,
            '@numFmtId' => 0,
            '@xfId'     => 0,
        ];
        $cellXfsAr[] = [
            '@applyFont' => 1,
            '@borderId'  => 0,
            '@fillId'    => 0,
            '@fontId'    => 1,
            '@numFmtId'  => 0,
            '@xfId'      => 0,
        ];

        // Define every style.
        foreach ($styles as $style) {

            // Lvd.
            $cellXf = [
                '@applyAlignment'    => 0,
                '@applyFill'         => 0,
                '@applyFont'         => 0,
                '@applyNumberFormat' => 0,
                '@borderId'          => 0,
                '@fillId'            => 0,
                '@fontId'            => 0,
                '@numFmtId'          => 0,
                '@xfId'              => 0,
            ];

            // Define Style's Fill.
            if ($style->hasFill() === true) {

                $fill = [
                    '@@' => [
                        'patternFill' => [
                            '@patternType' => 'solid',
                            '@@' => [
                                'fgColor' => [
                                    '@rgb' => $style->getFill()->getColor()->get()
                                ],
                                'bgColor' => [
                                    '@indexed' => '64',
                                ],
                            ],
                        ],
                    ],
                ];

                $fillsAr[]            = $fill;
                $cellXf['@fillId']    = ( count($fillsAr) - 1 );
                $cellXf['@applyFill'] = 1;
            }//end if

            // Define Style's Font.
            if ($style->hasFont() === true) {

                $fontsAr[] = ( new FontXml($this->xlsx, $style->getFont()) )->toXmlArray();

                $cellXf['@fontId']    = ( count($fontsAr) - 1 );
                $cellXf['@applyFont'] = 1;
            } else {
                $cellXf['@fontId']    = 1;
                $cellXf['@applyFont'] = 1;
            }//end if

            // Define Style's Align.
            if ($style->hasAlign() === true || $style->hasWrapText() === true) {
                $cellXf['@@']                           = [];
                $cellXf['@@']['alignment']              = [];
                $cellXf['@@']['alignment']['@wrapText'] = '1';

                if ($style->hasAlign('h') === true) {
                    $cellXf['@@']['alignment']['@horizontal'] = $style->getAlign()['h'];
                }
                if ($style->hasAlign('v') === true) {
                    $cellXf['@@']['alignment']['@vertical'] = $style->getAlign()['v'];
                }
                if ($style->hasWrapText() === true) {
                    $cellXf['@@']['alignment']['@wrapText'] = ( ( $style->getWrapText() === true ) ? '1' : '0' );
                }

                $cellXf['@applyAlignment'] = 1;
            }//end if

            // Define Style's Format.
            if ($style->hasFormat() === true) {

                $formatsAr[] = [
                    '@formatCode' => $style->getFormat()->getCode(),
                    '@numFmtId'   => $style->getFormat()->getIdForXlsx($this->xlsx),
                ];

                $cellXf['@numFmtId']          = $style->getFormat()->getIdForXlsx($this->xlsx);
                $cellXf['@applyNumberFormat'] = 1;
            }

            $cellXfsAr[] = $cellXf;
        }//end foreach

        $this->array['styleSheet']['@@']['numFmts']['@count']       = count($formatsAr);
        $this->array['styleSheet']['@@']['numFmts']['@@']['numFmt'] = $formatsAr;

        $this->array['styleSheet']['@@']['fonts']['@count']     = count($fontsAr);
        $this->array['styleSheet']['@@']['fonts']['@@']['font'] = $fontsAr;

        $this->array['styleSheet']['@@']['fills']['@count']     = count($fillsAr);
        $this->array['styleSheet']['@@']['fills']['@@']['fill'] = $fillsAr;

        $this->array['styleSheet']['@@']['cellXfs']['@count']   = count($cellXfsAr);
        $this->array['styleSheet']['@@']['cellXfs']['@@']['xf'] = $cellXfsAr;

        return $this;
    }
}
