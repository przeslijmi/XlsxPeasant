<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items;

/**
 * Num format definition used in Style.
 */
abstract class Format
{

    /**
     * Type of format (number, date).
     *
     * @var string
     */
    private $type;

    /**
     * Setter for id.
     *
     * @param integer $id Id of NumFormat.
     *
     * @since  v1.0
     * @throws SheetIdOtoranException When ID is below 1.
     * @return self
     */
    public function setId(?int $id = null) : self
    {

        // Find id if not given.
        if ($id === null) {
            // $id = $this->findSpareId($this->getXlsx()->getBook()->getSheets(false));
            $id = rand(100, 999);
        }

        // Check.
        // if ($id < 1) {
        //     throw new SheetIdOtoranException($id);
        // }

        // Set.
        $this->id = $id;

        return $this;
    }

    /**
     * Getter for id.
     *
     * @since  v1.0
     * @return integer
     */
    public function getId() : int
    {

        return $this->id;
    }

    /**
     * Setter for type.
     *
     * @param string $type Type of format (number, date).
     *
     * @since  v1.0
     * @return self
     */
    public function setType(string $type) : self
    {

        $this->type = $type;

        return $this;
    }

    /**
     * Getter for type.
     *
     * @since  v1.0
     * @return string
     */
    public function getType() : string
    {

        return $this->type;
    }
}
