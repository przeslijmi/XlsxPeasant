<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Reader\XmlFile;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\XlsxPeasant\Helpers\Tools as XlsxTools;
use Przeslijmi\XlsxPeasant\Reader;
use Przeslijmi\XlsxPeasant\Reader\XmlFile;
use Throwable;

/**
 * Worksheet XML file as object.
 */
class XlWorksheet extends XmlFile
{

    /**
     * Sheet's id `sheetId="3"`. Used also in sheet file name.
     *
     * @var integer
     */
    private $id;

    /**
     * Sheet's order number `r:id="rId2"`.
     *
     * @var integer
     */
    private $number;

    /**
     * Sheet's name.
     *
     * @var string
     */
    private $name;

    /**
     * Cache of row nodes.
     *
     * @var array
     */
    private $rowNodesCache;

    /**
     * Constructor.
     *
     * @param string $fileUri Uri of XML file.
     * @param Reader $reader  Parent Reader instance.
     *
     * @throws ClassFopException When sth went wrong on creation.
     */
    public function __construct(string $fileUri, Reader $reader)
    {

        try {

            // Save reader to parent.
            parent::__construct($fileUri, $reader);

            // Define this object.
            $this->setIdAndNumber();
            $this->setName();
            $this->setRels();

        } catch (Throwable $thr) {
            throw (new ClassFopException('creatingReaderWorksheet', $thr))
                ->addObjectInfos($reader);
        }
    }

    /**
     * Getter for `id` (numbers are used in file names, id's are defined in XML).
     *
     * @return integer
     */
    public function getId() : int
    {

        return $this->id;
    }

    /**
     * Getter for `number` (numbers are used in file names, id's are defined in XML).
     *
     * @return integer
     */
    public function getNumber() : int
    {

        return $this->number;
    }

    /**
     * Getter for `name`.
     *
     * @return string
     */
    public function getName() : string
    {

        return $this->name;
    }

    /**
     * Checks if this Sheet is using table of given id.
     *
     * Checks if there is a relation to file /tables/table[i].xml in rels file for this sheet.
     *
     * @param integer $tableNumber Id of table (starting with 1).
     *
     * @return boolean
     */
    public function doYouUseTable(int $tableNumber) : bool
    {

        // If this sheet has no table at all - it will not have any rels.
        if ($this->rels === null) {
            return false;
        }

        // Lvd.
        $searchFor = '../tables/table' . $tableNumber . '.xml';

        // Scan and return true if found.
        foreach ($this->rels->getElementsByTagName('Relationship') as $relNode) {
            if ($relNode->getAttribute('Target') === $searchFor) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return value of cell as string.
     *
     * @param integer $row Starting with 1 (not 0).
     * @param integer $col Starting with 1 (not 0).
     *
     * @throws ClassFopException When reaching cell value is impossible.
     * @return null|string String if cell exists and has value (arrays are imploded); null otherwise.
     */
    public function getCellValue(int $row, int $col) : ?string
    {

        try {

            // Lvd.
            $cell = $this->getCellNode($row, $col);

            // Fast lane.
            if ($cell === null) {
                return null;
            }

            // If cell has no value.
            if ($cell->getElementsByTagName('v')->item(0) === null) {
                return null;
            }

            // If cell has value in shares strings.
            if ($cell->getAttribute('t') === 's') {

                // Get from shared strings.
                $ssIndex    = (int) ( ( $cell->getElementsByTagName('v')->item(0) )->nodeValue );
                $valueParts = $this->getReader()->getXlSharedStrings()->getValue($ssIndex);

                return implode('', $valueParts);
            }

            // Finally - this is normal cell.
            return $cell->getElementsByTagName('v')->item(0)->nodeValue;

        } catch (Throwable $thr) {
            throw (new ClassFopException('gettingValueOfCell', $thr))
                ->addInfo('row', (string) $row)->addInfo('col', (string) $col);
        }//end try

        return null;
    }

    /**
     * Return XML cell node from XML file.
     *
     * @param integer $row Starting with 1 (not 0).
     * @param integer $col Starting with 1 (not 0).
     *
     * @return null|object XML node if exists, null otherwise.
     */
    public function getCellNode(int $row, int $col) : ?object
    {

        // Lvd.
        $rowNode = $this->getRowNode($row);

        // Fast lane.
        if ($rowNode === null) {
            return null;
        }

        // Create whole cell ref (eg. B4).
        $cellRef = XlsxTools::convNumberToRef($col) . $row;

        // Try to find and return it - outherwise return null.
        foreach ($rowNode->getElementsByTagName('c') as $cellNode) {
            if ($cellNode->getAttribute('r') === $cellRef) {
                return $cellNode;
            }
        }

        return null;
    }

    /**
     * Return row node for given row id.
     *
     * @param integer $row Starting with 1 (not 0).
     *
     * @version v1.0
     * @return  null|object
     */
    public function getRowNode(int $row) : ?object
    {

        // Fast lane.
        if (isset($this->rowNodesCache[$row]) === true) {

            // Lvd.
            $rowNode = $this->rowNodesCache[$row];

            // Nullify.
            if ($rowNode === 'null') {
                $rowNode = null;
            }

            return $rowNode;
        }

        // Scan and return.
        foreach ($this->contents->getElementsByTagName('row') as $rowNode) {
            if ((int) $rowNode->getAttribute('r') === $row) {

                // Save to cache.
                $this->rowNodesCache[$row] = $rowNode;

                // Delete from set.
                $rowNode->parentNode->removeChild($rowNode);

                // Return.
                return $rowNode;
            }
        }

        // Save that there is no row like this (it is nullifide in fast lane).
        $this->rowNodesCache[$row] = 'null';

        return null;
    }

    /**
     * Returns this worksheet dimension ref.
     *
     * Beware that dimension can be either cell ref `A1` if there is only one (or none) cell
     * in worksheet; but also it can return real ref `A1:P20` if more than one cell is used.
     *
     * @version v1.0
     * @return  string
     */
    public function getDimensionRef() : string
    {

        return $this->contents->getElementsByTagName('dimension')->item(0)->getAttribute('ref');
    }

    /**
     * Converts this worksheet dimension refs to array.
     *
     * ## Return example
     * ```
     * [
     *    'firstCell' => [ 1, 3 ],
     *    'lastCell'  => [ 3, 4 ],
     * ]
     * ```
     *
     * @version v1.0
     * @return  array
     */
    public function getDimensionRefAsArray() : array
    {

        // Lvd.
        $refs = $this->contents->getElementsByTagName('dimension')->item(0)->getAttribute('ref');

        // Alternative way to found.
        if ($refs === 'A1' || empty($refs) === true) {

            // Lvd.
            $rows      = 0;
            $cols      = 0;
            $dataNode  = ( $this->contents->getElementsByTagName('sheetData') ?? null );
            $rowsNodes = [];

            // How many rows there are.
            if ($dataNode !== null) {
                $rowsNodes = $dataNode[0]->getElementsByTagName('row');
                $rows      = (int) count($rowsNodes);
            }

            // How many cols there is maximum in every of first 25 rows.
            for ($r = 0; $r < min($rows, 25); ++$r) {
                $cols = max($cols, count($rowsNodes[$r]->getElementsByTagName('c')));
            }

            // Return ready number set.
            return [
              'firstCell' => [ 1, 1 ],
              'lastCell'  => [ $rows, $cols ],
            ];
        }

        return XlsxTools::convCellsRefToNumbers($refs);
    }

    /**
     * Setter for `id` and `number` - as both taken from worksheet file.
     *
     * @return self
     */
    private function setIdAndNumber() : self
    {

        // Lvd.
        $fileUri = str_replace('/', '\\', $this->getFileUri());

        // Use this number.
        $number = substr($fileUri, ( strrpos($fileUri, '\\worksheets\\sheet') + 17 ));
        $number = (int) ( substr($number, 0, -4) );

        // Use this id.
        $id = $this->getReader()->getXlWorkbook()->getSheetId($number);

        // Save.
        $this->number = $number;
        $this->id     = $id;

        return $this;
    }

    /**
     * Setter for `name`.
     *
     * @return self
     */
    private function setName() : self
    {

        // Lvd.
        $name = $this->getReader()->getXlWorkbook()->getSheetName($this->id);

        // Save name.
        $this->name = $name;

        return $this;
    }

    /**
     * Call to check if this worksheet has rels file and read it in if it has.
     *
     * @return self
     */
    private function setRels() : self
    {

        // Lvd.
        $namePart    = 'sheet' . $this->number . '.xml';
        $xmlFileUri  = str_replace('\\', '/', $this->getFileUri());
        $relsFileUri = str_replace($namePart, '_rels/' . $namePart . '.rels', $xmlFileUri);

        // If it exists add rels file to this sheet.
        if (file_exists($relsFileUri) === true) {
            $this->addRels($relsFileUri);
        }

        return $this;
    }
}
