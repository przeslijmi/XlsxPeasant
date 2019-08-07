<?php declare(strict_types=1);

/*
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */

namespace Przeslijmi\XlsxPeasant\Xmls;

use Przeslijmi\XlsxPeasant\Xml;

/**
 * XML nodes for `rels\.rels`.
 */
class RelsRels extends Xml
{

    /**
     * Constructor.
     *
     * @since v1.0
     */
    public function __construct()
    {

        // Define nodes.
        $this->array = [
            'Relationships' => [
                '@xmlns' => 'http://schemas.openxmlformats.org/package/2006/relationships',
                '@@'     => [
                    'Relationship' => [
                        [
                            '@Id'     => 'rId3',
                            '@Type'   => 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties',
                            '@Target' => 'docProps/app.xml',
                        ],
                        [
                            '@Id'     => 'rId2',
                            '@Type'   => 'http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties',
                            '@Target' => 'docProps/core.xml',
                        ],
                        [
                            '@Id'     => 'rId1',
                            '@Type'   => 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument',
                            '@Target' => 'xl/workbook.xml',
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
