<?php declare(strict_types=1);

namespace Przeslijmi\XlsxGenerator;

use Przeslijmi\Sivalidator\GeoProgression;

/**
 * Converter from PHP Array to XML (string).
 *
 * ## Usage examples
 *
 * ### One empty node
 * ```
 * $array = [ 'b' ];
 * $xml = new Xml($array);
 * echo $xml->toXml());
 * // <b />
 * ```
 *
 * ### Node with contents
 * ```
 * $array = [
 *     'b' => 'hello'
 * ];
 * $xml = new Xml($array);
 * echo $xml->toXml());
 * // <b>hello</b>
 * ```
 *
 * ### Node with contents and attributes
 * ```
 * $array = [
 *     'b' => [
 *         '@class' => 'aa',
 *         '@@' => 'hello',
 *     ],
 * ];
 * $xml = new Xml($array);
 * echo $xml->toXml());
 * // <b class="aa">hello</b>
 * ```
 *
 * ### Multiple nodes of the same type
 * ```
 * $array = [
 *     'b' => [
 *         [
 *             '@class' => 'aa',
 *             '@@' => 'hello',
 *         ],
 *         [
 *             '@class' => 'bb',
 *             '@@' => 'world',
 *         ],
 *     ],
 * ];
 * $xml = new Xml($array);
 * echo $xml->toXml());
 * // <b class="aa">hello</b>
 * // <b class="bb">world</b>
 * ```
 *
 * ### Schema depth
 * ```
 * $array = [
 *     'b' => [
 *         '@class' => 'aa',
 *         '@@' => [
 *             'anchor',
 *             'pointer',
 *             'span' => 'content',
 *         ],
 *     ],
 * ];
 * $xml = new Xml($array);
 * echo $xml->toXml());
 * // <b class="aa">
 * //     <anchor />
 * //     <pointer />
 * //     <span>content</span>
 * // </b>
 * ```
 *
 * ### Schema depth with duplications
 * ```
 * $array = [
 *     'b' => [
 *         '@class' => 'aa',
 *         '@@' => [
 *             'anchor',
 *             'pointer',
 *             'span' => [
 *                 [ '@@' => 'content1' ],
 *                 [ '@@' => 'content2' ],
 *             ],
 *         ],
 *     ],
 * ];
 * $xml = new Xml($array);
 * echo $xml->toXml());
 * // <b class="aa">
 * //     <anchor />
 * //     <pointer />
 * //     <span>content1</span>
 * //     <span>content2</span>
 * // </b>
 * ```
 */
class Xml
{

    /**
     * Configuration settings.
     */
    const NO_INDENTATION           = 1;
    const NO_NEW_LINES             = 2;
    const NO_NEW_LINE_AFTER_HEADER = 4;
    const NO_SPACE_ON_SHORTTAGS    = 8;

    /**
     * Contents of the whole XML as PHP array.
     *
     * @var array
     */
    protected $array = [];

    /**
     * Counter of depth of indentation.
     *
     * @var integer
     */
    private $depth = -1;

    /**
     * Multiplicator for depth identation (eg. 2 spaces, 4 spaces, etc.).
     *
     * @var integer
     */
    private $spaces = 4;

    /**
     * Configs for generation.
     *
     * @var array
     */
    private $configs = [];

    /**
     * Header of the document.
     *
     * @var string
     */
    private $header;

    /**
     * Which new line is used.
     *
     * @var string
     */
    private $newLine = PHP_EOL;

    /**
     * Constructor.
     *
     * @param array $array Optional XML contents.
     *
     * @since v1.0
     */
    public function __construct(?array $array = null)
    {

        if (is_null($array) === false) {
            $this->array = $array;
        }
    }

    /**
     * Converts array to XML string.
     *
     * @param integer $configs Configs integer as progression number.
     *
     * @since  v1.0
     * @return string
     */
    public function toXml(int $configs = 0) : string
    {

        // Call preparations.
        if (method_exists($this, 'prep') === true) {
            $this->prep();
        }

        // Save configs.
        if ($configs > 0) {
            $this->setConfigs($configs);
        }

        // Set configs.
        if (in_array(self::NO_NEW_LINES, $this->configs) === true) {
            $this->newLine = '';
        }

        // Add header.
        $result = $this->header;
        if (in_array(self::NO_NEW_LINE_AFTER_HEADER, $this->configs) === false) {
            $result .= PHP_EOL;
        }

        // Add rest of XML.
        $result .= $this->nodesToXml($this->array);

        // Format XML.
        $result = trim($result);

        return $result;
    }

    /**
     * Returns array with contents.
     *
     * @return array
     */
    public function toArray() : array
    {

        // Call preparations.
        if (method_exists($this, 'prep') === true) {
            $this->prep();
        }

        return $this->array;
    }

    /**
     * Getter for `$this->spaces`.
     *
     * @since  v1.0
     * @return integer
     */
    public function getSpaces() : int
    {

        return $this->spaces;
    }

    /**
     * Setter for `$this->spaces`.
     *
     * @param integer $spaces How many spaces use on indentation (4 by default).
     *
     * @since  v1.0
     * @return self
     */
    public function setSpaces(int $spaces) : self
    {

        // Check.
        if ($spaces < 0 || $spaces > 10) {
            die('Throw otoran 398498347');
        }

        // Save.
        $this->spaces = $spaces;

        return $this;
    }

    /**
     * Getter for `$this->header`.
     *
     * @since  v1.0
     * @return string
     */
    public function getHeader() : string
    {

        return $this->header;
    }

    /**
     * Setter for `$this->header`.
     *
     * @param string $header Needed header of XML file.
     *
     * @since  v1.0
     * @return self
     */
    public function setHeader(string $header) : self
    {

        $this->header = trim($header);

        return $this;
    }

    /**
     * Setter for `$this->configs`.
     *
     * @param integer $configs Progression number to read configurations in.
     *
     * @since  v1.0
     * @return self
     */
    public function setConfigs(int $configs) : self
    {

        $this->configs = GeoProgression::getProgression($configs);

        return $this;
    }

    /**
     * Draw array of nodes as XML.
     *
     * @param array $nodes Set of nodes.
     *
     * @since  v1.0
     * @return string
     */
    private function nodesToXml(array $nodes) : string
    {

        // Lvd.
        $result     = '';
        $thisIsNode = true;

        /*
         * At this point it is unclear whether it is onenode structure, eg.:
         * $name => [
         *     '@@' => 'contents'
         * ]
         * Or multinode structure, eg:
         * $name => [
         *     [
         *         '@@' => 'content1'
         *     ],
         *     [
         *         '@@' => 'content2'
         *     ]
         * ]
         * That is why name $nodeOrNodes is used. First example is an example of onenode - second
         * one is of multinodes (two nodes).
         */

        foreach ($nodes as $name => $nodeOrNodes) {

            // Shortest option, ie. $nodes was:
            // [ 0 => 'aaa', 1 => 'bbb' ]
            // So this is multionode structure and `<aaa /><bbb />` is expected.
            // Just move names, and leave value as null.
            if (is_numeric($name) === true) {

                // Throw if insted of 'aaa' or 'bbb' non-scalar is given.
                if (is_scalar($nodeOrNodes) === false) {
                    die('throw 0934w9fj349 in ' . $name);
                }

                // Save it (and stringify it).
                // At this moment it is also clear that it is onenode - not multinodes
                // but leave variable names unchanged - it will be done later.
                $name        = (string) $nodeOrNodes;
                $nodeOrNodes = null;
            }

            // Have to clarify which scenario it is here:
            // ```
            // [ 'deeperTag1',  'deeperTag2' ]
            // ```
            // or
            // ```
            // [ '@tagAttr1', '@tagAttr2' ]
            // ```
            // Whilst if it is first option - this is multinode structure of empty nodes.
            // If it is second option - this is onenode with parameters.
            if (is_array($nodeOrNodes) === true) {

                // Get keys, sort them and check if first key starts with `@`.
                $keys = array_keys($nodeOrNodes);
                sort($keys);
                $firstKey = (string) $keys[0];

                // If it is - this is second scenario - otherwise it is the first scenario.
                if (substr($firstKey, 0, 1) !== '@') {
                    $thisIsNode = false;
                }
            }

            // Finally we know if this is multinode or onenode!
            // If this is onenode - wrap it with extra array to make it multinode.
            // Otherwise leave it unchanged.
            if ($thisIsNode === true) {
                $nodes = [ $nodeOrNodes ];
            } else {
                $nodes = $nodeOrNodes;
            }

            // Draw every node.
            foreach ($nodes as $node) {
                $result .= $this->nodeToXml(
                    (string) $name,
                    $node
                );
            }
        }//end foreach

        return $result;
    }

    /**
     * Draw one node as XML.
     *
     * @param string            $name Name of node.
     * @param null|scalar|array $node Contents of node.
     *
     * @since  v1.0
     * @return string
     */
    private function nodeToXml(string $name, $node) : string
    {

        // Increase depth.
        ++$this->depth;

        // Lvd.
        $addNewLineBeforClosing = false;
        $contents               = '';

        // Check proper type of node.
        if (is_scalar($node) === false
            && is_array($node) === false
            && is_null($node) === false
        ) {
            die('Throw er89u93795274' . gettype($node));
        }

        // Check if node name is proper.
        $this->validateNodeName($name);

        // Check if there is any contents.
        if (isset($node['@@']) === true) {
            $contents = $node['@@'];
        }
        if (is_scalar($node) === true) {
            $contents = (string) $node;
        }

        // Draw beginning of the result.
        $result = $this->newLine . $this->indentToXml() . '<' . $name;

        // Draw attributes.
        if (is_array($node) === true) {
            $result .= $this->attributesToXml($node);
        }

        // Draw short node closing.
        if (is_scalar($contents) === true && strlen((string) $contents) === 0) {
            $result .= $this->closeNodeToXml($name, true);
            return $result;
        }

        // Draw end of the starting tag.
        $result .= '>';

        // Draw contents.
        if (is_array($contents) === true) {

            // Set for next iteration.
            $addNewLineBeforClosing = true;

            // Call to draw all nodes that are deeper.
            $result .= $this->nodesToXml($contents);
        } else {
            $result .= $contents;
        }

        // Draw ending tag.
        if ($addNewLineBeforClosing === true) {
            $result .= $this->newLine . $this->indentToXml();
        }
        $result .= $this->closeNodeToXml($name);

        return $result;
    }

    /**
     * Draw indentation as XML.
     *
     * @since  v1.0
     * @return string
     */
    private function indentToXml() : string
    {

        if (in_array(self::NO_INDENTATION, $this->configs) === true) {
            return '';
        }

        return str_pad('', ( $this->depth * $this->spaces ));
    }

    /**
     * Draw attributes of node as XML.
     *
     * @param array $node Array with attributes with @ as key suffix.
     *
     * @since  v1.0
     * @return string
     */
    private function attributesToXml(array $node) : string
    {

        // Lvd.
        $result = '';

        foreach ($node as $attrName => $attrValues) {

            // Lvd.
            $attrName = (string) $attrName;

            // This is not an attribute.
            if (substr($attrName, 0, 1) !== '@') {
                continue;
            }

            // This is also not an attribute - it is contents of node.
            if (substr($attrName, 0, 2) === '@@') {
                continue;
            }

            // Assume there are multiple parameters with this name.
            if (is_array($attrValues) === false) {
                $attrValues = [ $attrValues ];
            }

            // Draw every value of this attribute.
            foreach ($attrValues as $attrValue) {

                // Lvd.
                $attrName  = substr($attrName, 1);
                $attrValue = (string) $attrValue;

                // Validate.
                $this->validateAttributeName($attrName);
                $this->validateAttributeValue($attrName);

                // Draw result.
                $result .= ' ' . $attrName . '="' . $attrValue . '"';
            }
        }//end foreach

        return $result;
    }

    /**
     * Draw closing node as XML.
     *
     * @param string  $name  Name of the tag.
     * @param boolean $short Optional, false. If it has to be short tag or not.
     *
     * @since  v1.0
     * @return string
     */
    private function closeNodeToXml(string $name, bool $short = false) : string
    {

        // Lower depth.
        --$this->depth;

        // If short.
        if ($short === true) {

            if (in_array(self::NO_SPACE_ON_SHORTTAGS, $this->configs) === true) {
                return '/>';
            } else {
                return ' />';
            }
        }

        return '</' . $name . '>';
    }

    /**
     * Check if name of node (tag) is proper.
     *
     * @param string $name Name of node.
     *
     * @todo
     * @since  v1.0
     * @return void
     */
    private function validateNodeName(string $name) : void
    {
    }

    /**
     * Check if name of attribute is proper.
     *
     * @param string $name Name of attribute.
     *
     * @todo
     * @since  v1.0
     * @return void
     */
    private function validateAttributeName(string $name) : void
    {
    }

    /**
     * Check if value of attribute is proper.
     *
     * @param string $value Value of attribute.
     *
     * @todo
     * @since  v1.0
     * @return void
     */
    private function validateAttributeValue(string $value) : void
    {
    }
}
