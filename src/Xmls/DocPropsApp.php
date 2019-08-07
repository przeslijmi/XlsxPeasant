<?php declare(strict_types=1);

/*
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */

namespace Przeslijmi\XlsxPeasant\Xmls;

use Przeslijmi\XlsxPeasant\Xlsx;
use Przeslijmi\XlsxPeasant\Xml;

/**
 * XML nodes for `docProps\app.xml`.
 */
class DocPropsApp extends Xml
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
            'Properties' => [
                '@xmlns'    => 'http://schemas.openxmlformats.org/officeDocument/2006/extended-properties',
                '@xmlns:vt' => 'http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes',
                '@@'        => [
                    'Application'       => 'Microsoft Excel',
                    'DocSecurity'       => '0',
                    'ScaleCrop'         => 'false',
                    'HeadingPairs'      => [
                        '@@' => [
                            'vt:vector' => [
                                '@size'     => '2',
                                '@baseType' => 'variant',
                                '@@'        => [
                                    'vt:variant' => [
                                        [
                                            '@@' => [
                                                'vt:lpstr' => 'Arkusze',
                                            ],
                                        ],
                                        [
                                            '@@' => [
                                                'vt:i4' => '1',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'TitlesOfParts'     => [
                        '@@' => [
                            'vt:vector' => [
                                '@size'     => '1',
                                '@baseType' => 'lpstr',
                                '@@'        => [
                                    'vt:lpstr' => [
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'Company'           => '',
                    'LinksUpToDate'     => 'false',
                    'SharedDoc'         => 'false',
                    'HyperlinksChanged' => 'false',
                    'AppVersion'        => '16.0300',
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
        $sheets   = $this->xlsx->getBook()->getSheets();
        $sheetsAr = [];

        // Get all sheets.
        foreach ($sheets as $sheet) {
            $sheetsAr[] = $sheet->getName();
        }

        // Save TitlesOfParts.
        $this->array['Properties']['@@']['TitlesOfParts']['@@']['vt:vector']['@size']          = count($sheets);
        $this->array['Properties']['@@']['TitlesOfParts']['@@']['vt:vector']['@@']['vt:lpstr'] = $sheetsAr;

        // Save HeadingPairs.
        $this->array['Properties']['@@']['HeadingPairs']['@@']['vt:vector']['@@']['vt:variant'][1]['@@']['vt:i4'] = count($sheets);

        return $this;
    }
}
