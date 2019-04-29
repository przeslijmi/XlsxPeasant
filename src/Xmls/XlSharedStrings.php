<?php declare(strict_types=1);

/*
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */

namespace Przeslijmi\XlsxGenerator\Xmls;

use Przeslijmi\XlsxGenerator\Xlsx;
use Przeslijmi\XlsxGenerator\Xml;
use Przeslijmi\XlsxGenerator\Xmls\Common\FontXml;

/**
 * XML nodes for `xl\sharedStrings.xml`.
 */
class XlSharedStrings extends Xml
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
            'sst' => [
                '@count'       => '1',
                '@uniqueCount' => '1',
                '@xmlns'       => 'http://schemas.openxmlformats.org/spreadsheetml/2006/main',
                '@@'           => [
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

        $this->prepStrings();

        return $this;
    }

    /**
     * Preparation of `si` node.
     *
     * @since  v1.0
     * @return self
     */
    private function prepStrings() : self
    {

        // Lvd.
        $values  = $this->xlsx->getSharedStrings()->getIndex();
        $itemsAr = [];

        foreach ($values as $valueParts) {

            // Lvd.
            $thisParts = null;
            $oneSi     = [];

            if (count($valueParts) === 1) {

                // Define this term.
                $valuePart = $valueParts[0];
                $thisParts = $valuePart->getContents();

                // Save one shared string item.
                $oneSi = [
                    '@@' => [
                        't' => $thisParts,
                    ],
                ];

            } else {

                // Lvd.
                $thisParts = [];

                foreach ($valueParts as $valuePart) {

                    // Define standard.
                    $thisPart = [
                        '@@' => [
                        ],
                    ];

                    // Add `rPr` node if there is Font present.
                    if ($valuePart->hasFont() === true) {

                        $fontXml = new FontXml($this->xlsx, $valuePart->getFontMerged());
                        $fontXml->setForSharedStrings(true);

                        $thisPart['@@']['rPr'] = $fontXml->toXmlArray();
                    }

                    // Add contents (assume that there always is a contents).
                    $thisPart['@@']['t'] = $valuePart->getContents();

                    // Add to term.
                    $thisParts[] = $thisPart;

                }//end foreach

                // Save one shared string item.
                $oneSi = [
                    '@@' => [
                        'r' => $thisParts,
                    ],
                ];

            }//end if

            // Save all items.
            $itemsAr[] = $oneSi;

        }//end foreach

        // Save.
        $this->array['sst']['@@']['si'] = $itemsAr;

        return $this;
    }
}
