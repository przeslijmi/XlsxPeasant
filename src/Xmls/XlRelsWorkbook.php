<?php declare(strict_types=1);

/*
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */

namespace Przeslijmi\XlsxPeasant\Xmls;

use Przeslijmi\XlsxPeasant\Xlsx;
use Przeslijmi\XlsxPeasant\Xml;

/**
 * XML nodes for `xl\rels\workbook.xml.rels`.
 */
class XlRelsWorkbook extends Xml
{

    /**
     * Parent XLSx file.
     *
     * @var Xlsx
     */
    private $xlsx;

    /**
     * Schemas definitions for this XML.
     *
     * @var array
     */
    private $types = [
        'styles'        => 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles',
        'theme'         => 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/theme',
        'sheet'         => 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet',
        'sharedStrings' => 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings',
    ];

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
            'Relationships' => [
                '@xmlns' => 'http://schemas.openxmlformats.org/package/2006/relationships',
                '@@'     => [
                    'Relationship' => [
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

        $this->prepRelations();

        return $this;
    }

    /**
     * Preparation of `Relationship` node.
     *
     * @since  v1.0
     * @return self
     */
    private function prepRelations() : self
    {

        // Lvd.
        $maxRId        = 0;
        $relationships = [];

        // Add Sheets.
        foreach ($this->xlsx->getBook()->getSheets() as $sheet) {

            $relationships[] = [
                '@Id'     => 'rId' . $sheet->getId(),
                '@Type'   => $this->types['sheet'],
                '@Target' => 'worksheets/sheet' . $sheet->getId() . '.xml',
            ];

            // Count max ID.
            $maxRId = max($maxRId, $sheet->getId());
        }

        // Add the rest of relationships.
        $relationships[] = [
            '@Id'     => 'rId' . ( ++$maxRId ),
            '@Type'   => $this->types['styles'],
            '@Target' => 'styles.xml',
        ];
        $relationships[] = [
            '@Id'     => 'rId' . ( ++$maxRId ),
            '@Type'   => $this->types['theme'],
            '@Target' => 'theme/theme1.xml',
        ];
        $relationships[] = [
            '@Id'     => 'rId' . ( ++$maxRId ),
            '@Type'   => $this->types['sharedStrings'],
            '@Target' => 'sharedStrings.xml',
        ];

        // Save.
        $this->array['Relationships']['@@']['Relationship'] = $relationships;

        return $this;
    }
}
