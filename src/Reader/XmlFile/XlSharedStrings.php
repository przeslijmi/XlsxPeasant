<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Reader\XmlFile;

use Przeslijmi\XlsxPeasant\Reader;
use Przeslijmi\XlsxPeasant\Reader\XmlFile;

/**
 * SharedString XML file as object.
 */
class XlSharedStrings extends XmlFile
{

    /**
     * Returns array with values under given shared strings id.
     *
     * Even if cell has no value parts - it returns array with one element.
     *
     * @param integer $id Id of shared string.
     *
     * @since  v1.0
     * @return array
     */
    public function getValue(int $id) : array
    {

        // Lvd.
        $result = [];

        // Get node.
        $siNode = $this->contents->getElementsByTagName('si')->item($id);

        // Check if there are <r> subnodes or not.
        if ($siNode->getElementsByTagName('r')->length <= 1) {
            $result[] = $siNode->nodeValue;
        } else {
            foreach ($siNode->getElementsByTagName('r') as $rNode) {
                $result[] = $rNode->nodeValue;
            }
        }

        return $result;
    }
}
