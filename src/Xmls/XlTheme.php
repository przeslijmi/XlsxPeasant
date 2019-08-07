<?php declare(strict_types=1);

/*
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */

namespace Przeslijmi\XlsxPeasant\Xmls;

use Przeslijmi\XlsxPeasant\Xml;

/**
 * XML nodes for `xl\theme.xml`.
 */
class XlTheme extends Xml
{

    /**
     * Constructor.
     *
     * @since v1.0
     */
    public function __construct()
    {

        $this->array = [
            'a:theme' => [
                '@xmlns:a' => 'http://schemas.openxmlformats.org/drawingml/2006/main',
                '@name'    => 'Motyw pakietu Office',
                '@@'       => [
                    'a:themeElements' => [
                        '@@' => [
                            'a:clrScheme' => [
                                '@name' => 'Pakiet Office',
                                '@@' => [
                                    'a:dk1' => [
                                        '@@' => [
                                            'a:sysClr' => [
                                                '@val'     => 'windowText',
                                                '@lastClr' => '000000',
                                            ],
                                        ],
                                    ],
                                    'a:lt1' => [
                                        '@@' => [
                                            'a:sysClr' => [
                                                '@val'     => 'window',
                                                '@lastClr' => 'FFFFFF',
                                            ],
                                        ],
                                    ],
                                    'a:dk2' => [
                                        '@@' => [
                                            'a:srgbClr' => [
                                                '@val' => '44546A',
                                            ],
                                        ],
                                    ],
                                    'a:lt2' => [
                                        '@@' => [
                                            'a:srgbClr' => [
                                                '@val' => 'E7E6E6',
                                            ],
                                        ],
                                    ],
                                    'a:accent1' => [
                                        '@@' => [
                                            'a:srgbClr' => [
                                                '@val' => '4472C4',
                                            ],
                                        ],
                                    ],
                                    'a:accent2' => [
                                        '@@' => [
                                            'a:srgbClr' => [
                                                '@val' => 'ED7D31',
                                            ],
                                        ],
                                    ],
                                    'a:accent3' => [
                                        '@@' => [
                                            'a:srgbClr' => [
                                                '@val' => 'A5A5A5',
                                            ],
                                        ],
                                    ],
                                    'a:accent4' => [
                                        '@@' => [
                                            'a:srgbClr' => [
                                                '@val' => 'FFC000',
                                            ],
                                        ],
                                    ],
                                    'a:accent5' => [
                                        '@@' => [
                                            'a:srgbClr' => [
                                                '@val' => '5B9BD5',
                                            ],
                                        ],
                                    ],
                                    'a:accent6' => [
                                        '@@' => [
                                            'a:srgbClr' => [
                                                '@val' => '70AD47',
                                            ],
                                        ],
                                    ],
                                    'a:hlink' => [
                                        '@@' => [
                                            'a:srgbClr' => [
                                                '@val' => '0563C1',
                                            ],
                                        ],
                                    ],
                                    'a:folHlink' => [
                                        '@@' => [
                                            'a:srgbClr' => [
                                                '@val' => '954F72',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'a:fontScheme' => [
                                '@name' => 'Pakiet Office',
                                '@@' => [
                                    'a:majorFont' => [
                                        '@@' => [
                                            'a:latin' => [
                                                '@typeface' => 'Calibri Light',
                                                '@panose'   => '020F0302020204030204',
                                            ],
                                            'a:ea' => [
                                                '@typeface' => '',
                                            ],
                                            'a:cs' => [
                                                '@typeface' => '',
                                            ],
                                            'a:font' => [
                                                [
                                                    '@script'   => 'Jpan',
                                                    '@typeface' => '游ゴシック Light',
                                                ],
                                                [
                                                    '@script'   => 'Hang',
                                                    '@typeface' => '맑은 고딕',
                                                ],
                                                [
                                                    '@script'   => 'Hans',
                                                    '@typeface' => '等线 Light',
                                                ],
                                                [
                                                    '@script'   => 'Hant',
                                                    '@typeface' => '新細明體',
                                                ],
                                                [
                                                    '@script'   => 'Arab',
                                                    '@typeface' => 'Times New Roman',
                                                ],
                                                [
                                                    '@script'   => 'Hebr',
                                                    '@typeface' => 'Times New Roman',
                                                ],
                                                [
                                                    '@script'   => 'Thai',
                                                    '@typeface' => 'Tahoma',
                                                ],
                                                [
                                                    '@script'   => 'Ethi',
                                                    '@typeface' => 'Nyala',
                                                ],
                                                [
                                                    '@script'   => 'Beng',
                                                    '@typeface' => 'Vrinda',
                                                ],
                                                [
                                                    '@script'   => 'Gujr',
                                                    '@typeface' => 'Shruti',
                                                ],
                                                [
                                                    '@script'   => 'Khmr',
                                                    '@typeface' => 'MoolBoran',
                                                ],
                                                [
                                                    '@script'   => 'Knda',
                                                    '@typeface' => 'Tunga',
                                                ],
                                                [
                                                    '@script'   => 'Guru',
                                                    '@typeface' => 'Raavi',
                                                ],
                                                [
                                                    '@script'   => 'Cans',
                                                    '@typeface' => 'Euphemia',
                                                ],
                                                [
                                                    '@script'   => 'Cher',
                                                    '@typeface' => 'Plantagenet Cherokee',
                                                ],
                                                [
                                                    '@script'   => 'Yiii',
                                                    '@typeface' => 'Microsoft Yi Baiti',
                                                ],
                                                [
                                                    '@script'   => 'Tibt',
                                                    '@typeface' => 'Microsoft Himalaya',
                                                ],
                                                [
                                                    '@script'   => 'Thaa',
                                                    '@typeface' => 'MV Boli',
                                                ],
                                                [
                                                    '@script'   => 'Deva',
                                                    '@typeface' => 'Mangal',
                                                ],
                                                [
                                                    '@script'   => 'Telu',
                                                    '@typeface' => 'Gautami',
                                                ],
                                                [
                                                    '@script'   => 'Taml',
                                                    '@typeface' => 'Latha',
                                                ],
                                                [
                                                    '@script'   => 'Syrc',
                                                    '@typeface' => 'Estrangelo Edessa',
                                                ],
                                                [
                                                    '@script'   => 'Orya',
                                                    '@typeface' => 'Kalinga',
                                                ],
                                                [
                                                    '@script'   => 'Mlym',
                                                    '@typeface' => 'Kartika',
                                                ],
                                                [
                                                    '@script'   => 'Laoo',
                                                    '@typeface' => 'DokChampa',
                                                ],
                                                [
                                                    '@script'   => 'Sinh',
                                                    '@typeface' => 'Iskoola Pota',
                                                ],
                                                [
                                                    '@script'   => 'Mong',
                                                    '@typeface' => 'Mongolian Baiti',
                                                ],
                                                [
                                                    '@script'   => 'Viet',
                                                    '@typeface' => 'Times New Roman',
                                                ],
                                                [
                                                    '@script'   => 'Uigh',
                                                    '@typeface' => 'Microsoft Uighur',
                                                ],
                                                [
                                                    '@script'   => 'Geor',
                                                    '@typeface' => 'Sylfaen',
                                                ],
                                                [
                                                    '@script'   => 'Armn',
                                                    '@typeface' => 'Arial',
                                                ],
                                                [
                                                    '@script'   => 'Bugi',
                                                    '@typeface' => 'Leelawadee UI',
                                                ],
                                                [
                                                    '@script'   => 'Bopo',
                                                    '@typeface' => 'Microsoft JhengHei',
                                                ],
                                                [
                                                    '@script'   => 'Java',
                                                    '@typeface' => 'Javanese Text',
                                                ],
                                                [
                                                    '@script'   => 'Lisu',
                                                    '@typeface' => 'Segoe UI',
                                                ],
                                                [
                                                    '@script'   => 'Mymr',
                                                    '@typeface' => 'Myanmar Text',
                                                ],
                                                [
                                                    '@script'   => 'Nkoo',
                                                    '@typeface' => 'Ebrima',
                                                ],
                                                [
                                                    '@script'   => 'Olck',
                                                    '@typeface' => 'Nirmala UI',
                                                ],
                                                [
                                                    '@script'   => 'Osma',
                                                    '@typeface' => 'Ebrima',
                                                ],
                                                [
                                                    '@script'   => 'Phag',
                                                    '@typeface' => 'Phagspa',
                                                ],
                                                [
                                                    '@script'   => 'Syrn',
                                                    '@typeface' => 'Estrangelo Edessa',
                                                ],
                                                [
                                                    '@script'   => 'Syrj',
                                                    '@typeface' => 'Estrangelo Edessa',
                                                ],
                                                [
                                                    '@script'   => 'Syre',
                                                    '@typeface' => 'Estrangelo Edessa',
                                                ],
                                                [
                                                    '@script'   => 'Sora',
                                                    '@typeface' => 'Nirmala UI',
                                                ],
                                                [
                                                    '@script'   => 'Tale',
                                                    '@typeface' => 'Microsoft Tai Le',
                                                ],
                                                [
                                                    '@script'   => 'Talu',
                                                    '@typeface' => 'Microsoft New Tai Lue',
                                                ],
                                                [
                                                    '@script'   => 'Tfng',
                                                    '@typeface' => 'Ebrima',
                                                ],
                                            ],
                                        ],
                                    ],
                                    'a:minorFont' => [
                                        '@@' => [
                                            'a:latin' => [
                                                '@typeface' => 'Calibri',
                                                '@panose' => '020F0502020204030204',
                                            ],
                                            'a:ea' => [
                                                '@typeface' => '',
                                            ],
                                            'a:cs' => [
                                                '@typeface' => '',
                                            ],
                                            'a:font' => [
                                                [
                                                    '@script'   => 'Jpan',
                                                    '@typeface' => '游ゴシック',
                                                ],
                                                [
                                                    '@script'   => 'Hang',
                                                    '@typeface' => '맑은 고딕',
                                                ],
                                                [
                                                    '@script'   => 'Hans',
                                                    '@typeface' => '等线',
                                                ],
                                                [
                                                    '@script'   => 'Hant',
                                                    '@typeface' => '新細明體',
                                                ],
                                                [
                                                    '@script'   => 'Arab',
                                                    '@typeface' => 'Arial',
                                                ],
                                                [
                                                    '@script'   => 'Hebr',
                                                    '@typeface' => 'Arial',
                                                ],
                                                [
                                                    '@script'   => 'Thai',
                                                    '@typeface' => 'Tahoma',
                                                ],
                                                [
                                                    '@script'   => 'Ethi',
                                                    '@typeface' => 'Nyala',
                                                ],
                                                [
                                                    '@script'   => 'Beng',
                                                    '@typeface' => 'Vrinda',
                                                ],
                                                [
                                                    '@script'   => 'Gujr',
                                                    '@typeface' => 'Shruti',
                                                ],
                                                [
                                                    '@script'   => 'Khmr',
                                                    '@typeface' => 'DaunPenh',
                                                ],
                                                [
                                                    '@script'   => 'Knda',
                                                    '@typeface' => 'Tunga',
                                                ],
                                                [
                                                    '@script'   => 'Guru',
                                                    '@typeface' => 'Raavi',
                                                ],
                                                [
                                                    '@script'   => 'Cans',
                                                    '@typeface' => 'Euphemia',
                                                ],
                                                [
                                                    '@script'   => 'Cher',
                                                    '@typeface' => 'Plantagenet Cherokee',
                                                ],
                                                [
                                                    '@script'   => 'Yiii',
                                                    '@typeface' => 'Microsoft Yi Baiti',
                                                ],
                                                [
                                                    '@script'   => 'Tibt',
                                                    '@typeface' => 'Microsoft Himalaya',
                                                ],
                                                [
                                                    '@script'   => 'Thaa',
                                                    '@typeface' => 'MV Boli',
                                                ],
                                                [
                                                    '@script'   => 'Deva',
                                                    '@typeface' => 'Mangal',
                                                ],
                                                [
                                                    '@script'   => 'Telu',
                                                    '@typeface' => 'Gautami',
                                                ],
                                                [
                                                    '@script'   => 'Taml',
                                                    '@typeface' => 'Latha',
                                                ],
                                                [
                                                    '@script'   => 'Syrc',
                                                    '@typeface' => 'Estrangelo Edessa',
                                                ],
                                                [
                                                    '@script'   => 'Orya',
                                                    '@typeface' => 'Kalinga',
                                                ],
                                                [
                                                    '@script'   => 'Mlym',
                                                    '@typeface' => 'Kartika',
                                                ],
                                                [
                                                    '@script'   => 'Laoo',
                                                    '@typeface' => 'DokChampa',
                                                ],
                                                [
                                                    '@script'   => 'Sinh',
                                                    '@typeface' => 'Iskoola Pota',
                                                ],
                                                [
                                                    '@script'   => 'Mong',
                                                    '@typeface' => 'Mongolian Baiti',
                                                ],
                                                [
                                                    '@script'   => 'Viet',
                                                    '@typeface' => 'Arial',
                                                ],
                                                [
                                                    '@script'   => 'Uigh',
                                                    '@typeface' => 'Microsoft Uighur',
                                                ],
                                                [
                                                    '@script'   => 'Geor',
                                                    '@typeface' => 'Sylfaen',
                                                ],
                                                [
                                                    '@script'   => 'Armn',
                                                    '@typeface' => 'Arial',
                                                ],
                                                [
                                                    '@script'   => 'Bugi',
                                                    '@typeface' => 'Leelawadee UI',
                                                ],
                                                [
                                                    '@script'   => 'Bopo',
                                                    '@typeface' => 'Microsoft JhengHei',
                                                ],
                                                [
                                                    '@script'   => 'Java',
                                                    '@typeface' => 'Javanese Text',
                                                ],
                                                [
                                                    '@script'   => 'Lisu',
                                                    '@typeface' => 'Segoe UI',
                                                ],
                                                [
                                                    '@script'   => 'Mymr',
                                                    '@typeface' => 'Myanmar Text',
                                                ],
                                                [
                                                    '@script'   => 'Nkoo',
                                                    '@typeface' => 'Ebrima',
                                                ],
                                                [
                                                    '@script'   => 'Olck',
                                                    '@typeface' => 'Nirmala UI',
                                                ],
                                                [
                                                    '@script'   => 'Osma',
                                                    '@typeface' => 'Ebrima',
                                                ],
                                                [
                                                    '@script'   => 'Phag',
                                                    '@typeface' => 'Phagspa',
                                                ],
                                                [
                                                    '@script'   => 'Syrn',
                                                    '@typeface' => 'Estrangelo Edessa',
                                                ],
                                                [
                                                    '@script'   => 'Syrj',
                                                    '@typeface' => 'Estrangelo Edessa',
                                                ],
                                                [
                                                    '@script'   => 'Syre',
                                                    '@typeface' => 'Estrangelo Edessa',
                                                ],
                                                [
                                                    '@script'   => 'Sora',
                                                    '@typeface' => 'Nirmala UI',
                                                ],
                                                [
                                                    '@script'   => 'Tale',
                                                    '@typeface' => 'Microsoft Tai Le',
                                                ],
                                                [
                                                    '@script'   => 'Talu',
                                                    '@typeface' => 'Microsoft New Tai Lue',
                                                ],
                                                [
                                                    '@script'   => 'Tfng',
                                                    '@typeface' => 'Ebrima',
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'a:fmtScheme' => [
                                '@name' => 'Pakiet Office',
                                '@@' => [
                                    'a:fillStyleLst' => [
                                        '@@' => [
                                            'a:solidFill' => [
                                                '@@' => [
                                                    'a:schemeClr' => [
                                                        '@val' => 'phClr',
                                                    ],
                                                ],
                                            ],
                                            'a:gradFill' => [
                                                [
                                                    '@rotWithShape' => '1',
                                                    '@@' => [
                                                        'a:gsLst' => [
                                                            '@@' => [
                                                                'a:gs' => [
                                                                    [
                                                                        '@pos' => '0',
                                                                        '@@' => [
                                                                            'a:schemeClr' => [
                                                                                '@val' => 'phClr',
                                                                                '@@' => [
                                                                                    'a:lumMod' => [
                                                                                        '@val' => '110000',
                                                                                    ],
                                                                                    'a:satMod' => [
                                                                                        '@val' => '105000',
                                                                                    ],
                                                                                    'a:tint' => [
                                                                                        '@val' => '67000',
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                    [
                                                                        '@pos' => '50000',
                                                                        '@@' => [
                                                                            'a:schemeClr' => [
                                                                                '@val' => 'phClr',
                                                                                '@@' => [
                                                                                    'a:lumMod' => [
                                                                                        '@val' => '105000',
                                                                                    ],
                                                                                    'a:satMod' => [
                                                                                        '@val' => '103000',
                                                                                    ],
                                                                                    'a:tint' => [
                                                                                        '@val' => '73000',
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                    [
                                                                        '@pos' => '100000',
                                                                        '@@' => [
                                                                            'a:schemeClr' => [
                                                                                '@val' => 'phClr',
                                                                                '@@' => [
                                                                                    'a:lumMod' => [
                                                                                        '@val' => '105000',
                                                                                    ],
                                                                                    'a:satMod' => [
                                                                                        '@val' => '109000',
                                                                                    ],
                                                                                    'a:tint' => [
                                                                                        '@val' => '81000',
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                        'a:lin' => [
                                                            '@ang' => '5400000',
                                                            '@scaled' => '0',
                                                        ],
                                                    ],
                                                ],
                                                [
                                                    '@rotWithShape' => '1',
                                                    '@@' => [
                                                        'a:gsLst' => [
                                                            '@@' => [
                                                                'a:gs' => [
                                                                    [
                                                                        '@pos' => '0',
                                                                        '@@' => [
                                                                            'a:schemeClr' => [
                                                                                '@val' => 'phClr',
                                                                                '@@' => [
                                                                                    'a:satMod' => [
                                                                                        '@val' => '103000',
                                                                                    ],
                                                                                    'a:lumMod' => [
                                                                                        '@val' => '102000',
                                                                                    ],
                                                                                    'a:tint' => [
                                                                                        '@val' => '94000',
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                    [
                                                                        '@pos' => '50000',
                                                                        '@@' => [
                                                                            'a:schemeClr' => [
                                                                                '@val' => 'phClr',
                                                                                '@@' => [
                                                                                    'a:satMod' => [
                                                                                        '@val' => '110000',
                                                                                    ],
                                                                                    'a:lumMod' => [
                                                                                        '@val' => '100000',
                                                                                    ],
                                                                                    'a:shade' => [
                                                                                        '@val' => '100000',
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                    [
                                                                        '@pos' => '100000',
                                                                        '@@' => [
                                                                            'a:schemeClr' => [
                                                                                '@val' => 'phClr',
                                                                                '@@' => [
                                                                                    'a:lumMod' => [
                                                                                        '@val' => '99000',
                                                                                    ],
                                                                                    'a:satMod' => [
                                                                                        '@val' => '120000',
                                                                                    ],
                                                                                    'a:shade' => [
                                                                                        '@val' => '78000',
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                        'a:lin' => [
                                                            '@ang' => '5400000',
                                                            '@scaled' => '0',
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                    'a:lnStyleLst' => [
                                        '@@' => [
                                            'a:ln' => [
                                                [
                                                    '@w'    => '6350',
                                                    '@cap'  => 'flat',
                                                    '@cmpd' => 'sng',
                                                    '@algn' => 'ctr',
                                                    '@@' => [
                                                        'a:solidFill' => [
                                                            '@@' => [
                                                                'a:schemeClr' => [
                                                                    '@val' => 'phClr',
                                                                ],
                                                            ],
                                                        ],
                                                        'a:prstDash' => [
                                                            '@val' => 'solid',
                                                        ],
                                                        'a:miter' => [
                                                            '@lim' => '800000',
                                                        ],
                                                    ],
                                                ],
                                                [
                                                    '@w'    => '12700',
                                                    '@cap'  => 'flat',
                                                    '@cmpd' => 'sng',
                                                    '@algn' => 'ctr',
                                                    '@@' => [
                                                        'a:solidFill' => [
                                                            '@@' => [
                                                                'a:schemeClr' => [
                                                                    '@val' => 'phClr',
                                                                ],
                                                            ],
                                                        ],
                                                        'a:prstDash' => [
                                                            '@val' => 'solid',
                                                        ],
                                                        'a:miter' => [
                                                            '@lim' => '800000',
                                                        ],
                                                    ],
                                                ],
                                                [
                                                    '@w'    => '19050',
                                                    '@cap'  => 'flat',
                                                    '@cmpd' => 'sng',
                                                    '@algn' => 'ctr',
                                                    '@@' => [
                                                        'a:solidFill' => [
                                                            '@@' => [
                                                                'a:schemeClr' => [
                                                                    '@val' => 'phClr',
                                                                ],
                                                            ],
                                                        ],
                                                        'a:prstDash' => [
                                                            '@val' => 'solid',
                                                        ],
                                                        'a:miter' => [
                                                            '@lim' => '800000',
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                    'a:effectStyleLst' => [
                                        '@@' => [
                                            'a:effectStyle' => [
                                                [
                                                    '@@' => [
                                                        'a:effectLst',
                                                    ],
                                                ],
                                                [
                                                    '@@' => [
                                                        'a:effectLst',
                                                    ],
                                                ],
                                                [
                                                    '@@' => [
                                                        'a:effectLst' => [
                                                            '@@' => [
                                                                'a:outerShdw' => [
                                                                    '@blurRad'      => '57150',
                                                                    '@dist'         => '19050',
                                                                    '@dir'          => '5400000',
                                                                    '@algn'         => 'ctr',
                                                                    '@rotWithShape' => '0',
                                                                    '@@' => [
                                                                        'a:srgbClr' => [
                                                                            '@val' => '000000',
                                                                            '@@' => [
                                                                                'a:alpha' => [
                                                                                    '@val' => '63000',
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                    'a:bgFillStyleLst' => [
                                        '@@' => [
                                            'a:solidFill' => [
                                                [
                                                    '@@' => [
                                                        'a:schemeClr' => [
                                                            '@val' => 'phClr',
                                                        ],
                                                    ],
                                                ],
                                                [
                                                    '@@' => [
                                                        'a:schemeClr' => [
                                                            '@val' => 'phClr',
                                                            '@@'   => [
                                                                'a:tint' => [
                                                                    '@val' => '95000',
                                                                ],
                                                                'a:satMod' => [
                                                                    '@val' => '170000',
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                            'a:gradFill' => [
                                                [
                                                    '@rotWithShape' => '1',
                                                    '@@' => [
                                                        'a:gsLst' => [
                                                            '@@' => [
                                                                'a:gs' => [
                                                                    [
                                                                        '@pos' => '0',
                                                                        '@@' => [
                                                                            'a:schemeClr' => [
                                                                                '@val' => 'phClr',
                                                                                '@@' => [
                                                                                    'a:tint' => [
                                                                                        '@val' => '93000',
                                                                                    ],
                                                                                    'a:satMod' => [
                                                                                        '@val' => '150000',
                                                                                    ],
                                                                                    'a:shade' => [
                                                                                        '@val' => '98000',
                                                                                    ],
                                                                                    'a:lumMod' => [
                                                                                        '@val' => '102000',
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                    [
                                                                        '@pos' => '50000',
                                                                        '@@' => [
                                                                            'a:schemeClr' => [
                                                                                '@val' => 'phClr',
                                                                                '@@' => [
                                                                                    'a:tint' => [
                                                                                        '@val' => '98000',
                                                                                    ],
                                                                                    'a:satMod' => [
                                                                                        '@val' => '130000',
                                                                                    ],
                                                                                    'a:shade' => [
                                                                                        '@val' => '90000',
                                                                                    ],
                                                                                    'a:lumMod' => [
                                                                                        '@val' => '103000',
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                    [
                                                                        '@pos' => '100000',
                                                                        '@@' => [
                                                                            'a:schemeClr' => [
                                                                                '@val' => 'phClr',
                                                                                '@@' => [
                                                                                    'a:shade' => [
                                                                                        '@val' => '63000',
                                                                                    ],
                                                                                    'a:satMod' => [
                                                                                        '@val' => '120000',
                                                                                    ],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                        'a:lin' => [
                                                            '@ang' => '5400000',
                                                            '@scaled' => '0',
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'a:objectDefaults'    => null,
                    'a:extraClrSchemeLst' => null,
                    'a:extLst'            => [
                        '@@' => [
                            'a:ext' => [
                                '@uri' => '{05A4C25C-085E-4340-85A3-A5531E510DB2}',
                                '@@'   => [
                                    'thm15:themeFamily' => [
                                        '@xmlns:thm15' => 'http://schemas.microsoft.com/office/thememl/2012/main',
                                        '@name' => 'Office Theme',
                                        '@id'   => '{62F939B6-93AF-4DB8-9C6B-D6C7DFDC589F}',
                                        '@vid'  => '{4A3C46E8-61CC-4603-A589-7422A47A8E4A}',
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
}
