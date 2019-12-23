<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Reader\XmlFile;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\KeyDonoexException;
use Przeslijmi\Sexceptions\Exceptions\ObjectDonoexException;
use Przeslijmi\XlsxPeasant\Reader;
use Przeslijmi\XlsxPeasant\Reader\XmlFile;

/**
 * Reads and translates XML from `workbook.xml` file.
 */
class XlWorkbook extends XmlFile
{

    /**
     * Returns sheet name that has given id.
     *
     * @param integer $id Existing Sheet's id.
     *
     * @since  v1.0
     * @throws KeyDonoexException    When sheet with this id does not exists.
     * @throws ObjectDonoexException When sheet with this id does not exists.
     * @return string
     */
    public function getSheetName(int $id) : string
    {

        // Try to find.
        foreach ($this->contents->getElementsByTagName('sheet') as $sheetNode) {
            if ((int) $sheetNode->getAttribute('sheetId') === $id) {
                return (string) $sheetNode->getAttribute('name');
            }
        }

        // Nothing has been found - prepare to throw - get all ids.
        $range = [];
        foreach ($this->contents->getElementsByTagName('sheet') as $sheetNode) {
            $range[] = (int) $sheetNode->getAttribute('sheetId');
        }

        // Throw.
        try {
            throw new KeyDonoexException('sheetId', $range, (string) $id);
        } catch (Exception $exc) {
            throw (new ObjectDonoexException('Sheet', $exc))->addObjectInfos($this->getReader());
        }
    }

    /**
     * Returns this sheet id for given sheet number (from attribute sheetId in XML).
     *
     * @param integer $number Number (not id of Sheet [see XlWorksheet fi disambigustation]).
     *
     * @since  v1.0
     * @throws ObjectDonoexException When no <sheet> node is present inside Workbook XML contents.
     * @return integer Sheet's id.
     */
    public function getSheetId(int $number) : int
    {

        // Get node.
        $node = $this->contents->getElementsByTagName('sheet')[( $number - 1 )];

        // If node for this sheet number does not exists.
        if ($node === null) {
            throw (new ObjectDonoexException('XlsxSheet'))
                ->addInfo('number', (string) $number)
                ->addObjectInfos($this->getReader());
        }

        // It exists - return node.
        $id = (int) $node->getAttribute('sheetId');

        return $id;
    }
}
