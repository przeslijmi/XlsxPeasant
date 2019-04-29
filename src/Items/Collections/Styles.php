<?php declare(strict_types=1);

namespace Przeslijmi\XlsxGenerator\Items\Collections;

use Przeslijmi\XlsxGenerator\Items;
use Przeslijmi\XlsxGenerator\Items\Cell;
use Przeslijmi\XlsxGenerator\Items\Style;
use Przeslijmi\XlsxGenerator\Xlsx;

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
     * @var integer
     */
    private $lastIndex = 0;

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
                'id' => $this->lastIndex,
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
