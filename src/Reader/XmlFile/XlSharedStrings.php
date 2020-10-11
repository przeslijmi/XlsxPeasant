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
     * Saves information if there was interest on this string id.
     *
     * Cache is saved only for strings for which at least one question has been asked.
     *
     * @var boolean[]
     */
    private $asked;

    /**
     * Cache of string.
     *
     * @var array
     */
    private $cache;

    /**
     * Returns array with values under given shared strings id.
     *
     * Even if cell has no value parts - it returns array with one element.
     *
     * @param integer $id Id of shared string.
     *
     * @return array
     */
    public function getValue(int $id) : array
    {

        if (isset($this->asked[$id]) === true && isset($this->cache[$id]) === true) {
            return $this->cache[$id];
        }

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

        // Save.
        if (isset($this->asked[$id]) === false) {
            $this->asked[$id] = true;
        } else {
            $this->cache[$id] = $result;
        }

        return $result;
    }
}
