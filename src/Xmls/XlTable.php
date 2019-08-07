<?php declare(strict_types=1);

/*
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */

namespace Przeslijmi\XlsxPeasant\Xmls;

use Przeslijmi\XlsxPeasant\Xml;
use Przeslijmi\XlsxPeasant\Items\Table;
use Przeslijmi\XlsxPeasant\Items\Cell;
use Przeslijmi\XlsxPeasant\Helpers\Tools as XlsxTools;

/**
 * XML nodes for `xl\tables\table*.xml`.
 */
class XlTable extends Xml
{

    /**
     * Parent Table.
     *
     * @var Table
     */
    private $table;

    /**
     * Constructor.
     *
     * @param Table $table Table to import to this XML.
     *
     * @since v1.0
     */
    public function __construct(Table $table)
    {

        // Save parent.
        $this->table = $table;

        // Define nodes.
        $this->array = [
            'table' => [
                '@xmlns'          => 'http://schemas.openxmlformats.org/spreadsheetml/2006/main',
                '@xmlns:mc'       => 'http://schemas.openxmlformats.org/markup-compatibility/2006',
                '@mc:Ignorable'   => 'xr xr3',
                '@xmlns:xr'       => 'http://schemas.microsoft.com/office/spreadsheetml/2014/revision',
                '@xmlns:xr3'      => 'http://schemas.microsoft.com/office/spreadsheetml/2016/revision3',
                '@id'             => null,
                '@xr:uid'         => null,
                '@name'           => null,
                '@displayName'    => null,
                '@ref'            => null,
                '@totalsRowShown' => null,
                '@@' => [
                    'autoFilter' => [
                        '@ref'    => 'A4:C11',
                        '@xr:uid' => '{CAA3E3D2-0635-487C-9A4F-01F3631ED85E}',
                    ],
                    'tableColumns' => [
                        '@count' => '3',
                        '@@' => [
                            'tableColumn' => [
                            ],
                        ],
                    ],
                    'tableStyleInfo' => [
                        '@name'              => 'TableStyleDark9',
                        '@showFirstColumn'   => '0',
                        '@showLastColumn'    => '0',
                        '@showRowStripes'    => '1',
                        '@showColumnStripes' => '0',
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

        $this->array['table']['@id']             = $this->table->getId();
        $this->array['table']['@xr:uid']         = $this->table->getUuid();
        $this->array['table']['@name']           = $this->table->getName();
        $this->array['table']['@displayName']    = $this->table->getName();
        $this->array['table']['@ref']            = $this->table->getDimensionRef();
        $this->array['table']['@totalsRowShown'] = $this->table->countRows();

        $this->array['table']['@@']['autoFilter']['@ref']    = $this->table->getDimensionRef();
        $this->array['table']['@@']['autoFilter']['@xr:uid'] = XlsxTools::createUuid();

        $this->prepColumns();

        return $this;
    }

    /**
     * Preparation of `tableColumns` node.
     *
     * @since  v1.0
     * @return self
     */
    private function prepColumns() : self
    {

        // Lvd.
        $colsAr = [];

        // Foreach Row.
        foreach ($this->table->getColumns() as $id => $column) {

            // Add column.
            $colsAr[] = [
                '@id'      => $column->getId(),
                '@xr3:uid' => $column->getUuid(),
                '@name'    => $column->getName(),
            ];
        }

        // Save.
        $this->array['table']['@@']['tableColumns']['@count']            = count($this->table->getColumns());
        $this->array['table']['@@']['tableColumns']['@@']['tableColumn'] = $colsAr;

        return $this;
    }
}
