<?php
/**
 * File Description:
 *
 * @category
 * @package
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2013
 * @license    /license.txt
 */
class ConfigTest extends PHPUnit_Framework_TestCase
{
    public $config;

    public function setUp()
    {
        require_once '../core/lib/Config.php';
        $configTest = array(0 => 'apples',
                            1 => 'pears',
                            2 => 'bananas');
        $this->config = new \fmvc\core\lib\Config($configTest);
    }

    public function tearDown() {}

    /**
     * @expectedException Exception
     */
    public function testNullConstructor() {
        $test = new \fmvc\core\lib\Config();
    }

    public function testConstructor()
    {
        $cfg = $this->config->c_config;
        $this->assertEquals('apples', $cfg[0]);
        $this->assertEquals(3, count($cfg));
    }

    public function testItem()
    {
        $this->assertEquals('apples', $this->config->item(0));
        $this->assertEquals(3, count($this->config->item()));
    }

    public function testSetItem()
    {
        $this->config->set('value', 'testValue');
        $this->assertEquals('testValue', $this->config->item('value'));
    }

    /**
     * @expectedException Exception
     */
    public function testSetNull() {
        $this->config->set('item');
    }
}
