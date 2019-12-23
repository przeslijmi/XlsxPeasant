<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant\Exceptions;

use Przeslijmi\Sexceptions\Exceptions\ValueWrosynException;
use Throwable;

/**
 * Ordered XML structur has a misteake.
 *
 * This is wrong:
 * ```
 * 0 => [ 'aaa', 'bbb' ],
 * '0' => [ 'aaa', 'bbb' ],
 * ```
 *
 * This is proper:
 * ```
 * 'number0' => [ 'aaa', 'bbb' ],
 * 'zero' => [ 'aaa', 'bbb' ],
 * ```
 *
 * XML rule: **Names cannot start with a number or punctuation character**.
 */
class XmlNodeNameWrosynException extends ValueWrosynException
{

    /**
     * Constructor.
     *
     * @param string $nodeName Given name of node.
     *
     * @since v1.0
     */
    public function __construct(string $nodeName)
    {

        // Lvd.
        $hint = 'Node name in XML cannot start with a number, but `' . $nodeName . '` was given.';

        // Define.
        $this->addInfo('context', 'xmlNodeName');
        $this->addInfo('value', $nodeName);
        $this->addInfo('hint', $hint);
    }
}
