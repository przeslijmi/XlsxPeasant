<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Items;

use Przeslijmi\XlsxPeasant\Helpers\Tools as XlsxTools;

/**
 * Parent abstract class for all conditional formats.
 */
abstract class ConditionalFormat
{

    private $uuid;

    public function __construct()
    {

        $this->uuid = XlsxTools::createUuid();
    }

    public function getUuid()
    {

        return $this->uuid;
    }
}
