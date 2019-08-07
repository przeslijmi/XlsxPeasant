<?php declare(strict_types=1);

/*
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */

namespace Przeslijmi\XlsxPeasant\Xmls;

use Przeslijmi\XlsxPeasant\Items\Sheet;
use Przeslijmi\XlsxPeasant\Xml;

/**
 * XML nodes for `xl\worksheets\_rels\sheet1.xml.rels`.
 */
class XlRelsWorksheet extends Xml
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
     *
     * @since v1.0
     */
    public function __construct(Sheet $sheet)
    {

        // Save parent.
        $this->sheet = $sheet;

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

        // Add Tables of this Sheet.
        foreach ($this->sheet->getTables() as $table) {

            // Add next relation to Table of this Sheet.
            $relationships[] = [
                '@Id'     => 'rId' . $table->getId(),
                '@Type'   => 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/table',
                '@Target' => '../tables/table' . $table->getId() . '.xml',
            ];

            // Count max ID.
            $maxRId = max($maxRId, $table->getId());
        }

        // Save.
        $this->array['Relationships']['@@']['Relationship'] = $relationships;

        return $this;
    }
}
