<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items;

use Przeslijmi\XlsxPeasant\Helpers\Tools as XlsxTools;

/**
 * Parent abstract class for all conditional formats.
 */
abstract class ConditionalFormat
{

    /**
     * UUID of Conditional Format object.
     *
     * @var string
     */
    private $uuid;

    /**
     * Constructor.
     *
     * @since v1.0
     */
    public function __construct()
    {

        $this->uuid = XlsxTools::createUuid();
    }

    /**
     * Getter for UUID.
     *
     * @return string
     */
    public function getUuid()
    {

        return $this->uuid;
    }
}
