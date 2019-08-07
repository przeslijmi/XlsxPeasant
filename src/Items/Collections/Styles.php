<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items\Collections;

use Przeslijmi\XlsxPeasant\Items;
use Przeslijmi\XlsxPeasant\Items\Cell;
use Przeslijmi\XlsxPeasant\Items\Style;
use Przeslijmi\XlsxPeasant\Xlsx;

/**
 * Collection of Style objects.
 */
class Styles extends Items
{

    /**
     * Actual collection.
     *
     * @var array
     */
    private $index = [];

    /**
     * Collection index counter.
     *
     * Beginning index is 1 becuase first available Style index is 2 beceasue there are two default styles
     * defined (id: 0 & 1).
     *
     * @var integer
     */
    private $lastIndex = 1;

    /**
     * Register Style in collection.
     *
     * @param Style $style Style to be registerd.
     *
     * @since  v1.0
     * @return void
     */
    public function registerStyle(Style $style) : void
    {

        // Lvd.
        $signature = $style->getSignature();

        // Add if not exists.
        if (isset($this->index[$signature]) === false) {

            // Increase index counter.
            ++$this->lastIndex;

            // Add.
            $this->index[$signature] = [
                'style' => $style,
                'id'    => $this->lastIndex,
            ];
        }

        // Call back to Style to set ID.
        $style->setId($this->index[$signature]['id']);
    }

    /**
     * Get whole index but without signatures - only Style[].
     *
     * @since  v1.0
     * @return Style[]
     */
    public function getIndex() : array
    {

        // Lvd.
        $result = [];

        // Create.
        foreach ($this->index as $styleInfo) {
            $result[] = $styleInfo['style'];
        }

        return $result;
    }
}
