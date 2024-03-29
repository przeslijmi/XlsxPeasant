<?php declare(strict_types=1);

/*
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */

namespace Przeslijmi\XlsxPeasant\Xmls;

use Przeslijmi\XlsxPeasant\Xml;

/**
 * XML nodes for `docProps\core.xml`.
 */
class DocPropsCore extends Xml
{

    /**
     * Constructor.
     */
    public function __construct()
    {

        // Define nodes.
        $this->array = [
            'cp:coreProperties' => [
                '@xmlns:cp'       => 'http://schemas.openxmlformats.org/package/2006/metadata/core-properties',
                '@xmlns:dc'       => 'http://purl.org/dc/elements/1.1/',
                '@xmlns:dcterms'  => 'http://purl.org/dc/terms/',
                '@xmlns:dcmitype' => 'http://purl.org/dc/dcmitype/',
                '@xmlns:xsi'      => 'http://www.w3.org/2001/XMLSchema-instance',
                '@@'              => [
                    'dc:creator'        => 'XlsxPeasant',
                    'cp:lastModifiedBy' => 'XlsxPeasant',
                    'dcterms:created'   => [
                        '@xsi:type' => 'dcterms:W3CDTF',
                        '@@'        => '2019-04-23T10:00:38Z',
                    ],
                    'dcterms:modified'  => [
                        '@xsi:type' => 'dcterms:W3CDTF',
                        '@@'        => '2019-04-23T10:00:47Z',
                    ],
                ],
            ],
        ];

        $this->setConfigs(Xml::NO_INDENTATION | Xml::NO_NEW_LINES | Xml::NO_SPACE_ON_SHORTTAGS | Xml::NO_VALIDATION_NODE_NAME | Xml::NO_VALIDATION_ATTR_VALUE | Xml::NO_VALIDATION_ATTR_NAME);
        $this->setHeader('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>');

        parent::__construct();
    }
}
