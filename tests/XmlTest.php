<?php declare(strict_types=1);

namespace Przeslijmi\XlsxPeasant;

use PHPUnit\Framework\TestCase;
use Przeslijmi\XlsxPeasant\Exceptions\XmlNodeNameWrosynException;
use Przeslijmi\XlsxPeasant\Exceptions\XmlNodeValueWrotypeException;
use Przeslijmi\XlsxPeasant\Exceptions\XmlSpacingOtoranException;
use Przeslijmi\XlsxPeasant\Xml;
use stdClass;

/**
 * Methods for testing XML generator.
 */
final class XmlTest extends TestCase
{

    /**
     * Array to be tested.
     *
     * @var array
     */
    private $array = [
        'b' => [
            '@class' => 'aa',
            'sth' => 'bb',
            '@@' => [
                'anchor',
                'pointer' => [],
                'span' => [
                    [ '@@' => 'content1' ],
                    [ '@@' => 'content2' ],
                ],
                'empty' => null,
            ],
        ],
        'c' => [ '1', '2', '3' ],
    ];

    /**
     * Tests if generating XML works properly.
     *
     * @return void
     */
    public function testIfGeneratingWorks() : void
    {

        // Generate.
        $xml = new Xml($this->array);
        $xml->setSpaces(4);
        $xml->setHeader('<?xml?>');

        // Lvd.
        $nl = "\r\n";

        // Prepare.
        $stringLong   = '<?xml?>' . $nl . $nl . '<b class="aa">' . $nl . '    <anchor />' . $nl;
        $stringLong  .= '    <pointer />' . $nl . '    <span>content1</span>' . $nl . '    <span>';
        $stringLong  .= 'content2</span>' . $nl . '    <empty />' . $nl . '</b>' . $nl . '<c>1</c>';
        $stringLong  .= $nl . '<c>2</c>' . $nl . '<c>3</c>';
        $stringShort  = '<?xml?><b class="aa"><anchor/><pointer/><span>content1</span>';
        $stringShort .= '<span>content2</span><empty/></b><c>1</c><c>2</c><c>3</c>';

        // Test.
        $this->assertEquals($stringLong, $xml->toXml());
        $this->assertEquals($stringShort, $xml->toXml(15));
        $this->assertEquals($this->array, $xml->toArray());
        $this->assertEquals(4, $xml->getSpaces());
        $this->assertEquals('<?xml?>', $xml->getHeader());
    }

    /**
     * Tests if generating XML with parent class works properly.
     *
     * @return void
     */
    public function testIfGeneratingFromParentClassWorks() : void
    {

        // Define parent class.
        $parentXml = new class($this->array) extends Xml
        {

            /**
             * Preparation method to update `$this->array` according to current values.
             *
             * @return self
             */
            public function prep() : self
            {

                $this->array['extra'] = [ '1', '2', '3' ];

                return $this;
            }
        };

        // Prepare array.
        $arrayToExpect          = $this->array;
        $arrayToExpect['extra'] = [ '1', '2', '3' ];

        // Prepare string.
        $stringToExpect  = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><b class="aa">';
        $stringToExpect .= '<anchor /><pointer /><span>content1</span><span>content2</span><empty />';
        $stringToExpect .= '</b><c>1</c><c>2</c><c>3</c><extra>1</extra><extra>2</extra><extra>3</extra>';

        // Test.
        $this->assertEquals($arrayToExpect, $parentXml->toArray());
        $this->assertEquals($stringToExpect, $parentXml->toXml(7));
    }

    /**
     * Test if setting spacing above 10 throws.
     *
     * @return void
     */
    public function testIfToLowSpacingThrows() : void
    {

        // Prepare.
        $this->expectException(XmlSpacingOtoranException::class);

        // Create.
        $xml = new Xml();
        $xml->setSpaces(-2);
    }

    /**
     * Test if setting spacing below 0 throws.
     *
     * @return void
     */
    public function testIfToBigSpacingThrows() : void
    {

        // Prepare.
        $this->expectException(XmlSpacingOtoranException::class);

        // Create.
        $xml = new Xml();
        $xml->setSpaces(20);
    }

    /**
     * Test if giving node with wrong name throws.
     *
     * @return void
     */
    public function testIfToMailformedNodeNameThrows() : void
    {

        // Prepare.
        $this->expectException(XmlNodeNameWrosynException::class);

        // Create.
        $xml = new Xml([
            0 => [ 'test1', 'test2' ],
        ]);
        $xml->toXml();
    }

    /**
     * Test if giving non scalar, non array, non null value of node will throw.
     *
     * @return void
     */
    public function testIfToMailformedNodeValueThrows() : void
    {

        // Prepare.
        $this->expectException(XmlNodeValueWrotypeException::class);

        // Create.
        $xml = new Xml([
            'test' => new stdclass(),
        ]);
        $xml->toXml();
    }

    /**
     * Test if giving nonclear instruction will effect in ignoring nodes.
     *
     * BEWARE! This is a discussed behaviour - it is not clear if it is good or bad. This test is here
     * to make sure that you acknowledged this behaviour.
     *
     * @return void
     */
    public function testIfParserIsIgnoringUnclearNodes() : void
    {

        // Lvd.
        $nl = "\r\n";

        // Create.
        $xml = new Xml([
            'element' => [
                '@class1' => 'aa',
                '@class2' => 'aa',
                'sth' => 'bb',
                'sth2' => 'bb',
            ],
        ]);
        $xml->setHeader('');

        // Test.
        $this->assertEquals('<element class1="aa" class2="aa" />', $xml->toXml());

        // Create.
        $xml = new Xml([
            'element' => [
                'sth' => 'bb',
                'sth2' => 'bb',
            ],
        ]);
        $xml->setHeader('');

        // Test.
        $this->assertEquals('<element>bb</element>' . $nl . '<element>bb</element>', $xml->toXml());
    }
}
