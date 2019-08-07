<?php declare(strict_types=1);

/*
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */

namespace Przeslijmi\XlsxPeasant\Xmls;

use Przeslijmi\XlsxPeasant\Xlsx;
use Przeslijmi\XlsxPeasant\Xml;
use Przeslijmi\XlsxPeasant\Xmls\Common\FontXml;

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
                '@xmlns'       => 'http://schemas.openxmlformats.org/spreadsheetml/2006/main',
                '@count'       => '1',
                '@uniqueCount' => '1',
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
     *
     * @phpcs:disable Zend.NamingConventions.ValidVariableName.ContainsNumbers
     */
    private function prepStrings() : self
    {

        // Lvd.
        $values     = $this->xlsx->getSharedStrings()->getIndex();
        $itemsAr    = [];
        $itemsMd5Ar = [];

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

                foreach ($valueParts as $vpId => $valuePart) {

                    // Define standard.
                    $thisPart = [
                        '@@' => [
                        ],
                    ];

                    // Add `rPr` node if there is Font present or this is more then first part (next parts
                    // always need Font definition - even if there is any given)..
                    if ($vpId >= 1 || $valuePart->hasFont() === true) {

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
            $itemsAr[]    = $oneSi;
            $itemsMd5Ar[] = md5(serialize($oneSi));

        }//end foreach

        // Save.
        $this->array['sst']['@@']['si']     = $itemsAr;
        $this->array['sst']['@count']       = count($itemsAr);
        $this->array['sst']['@uniqueCount'] = count(array_unique($itemsMd5Ar));

        return $this;
    }
}
