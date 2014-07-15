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
use \fmvc\core\lib\Input;

if (! session_id()) {
    session_start();
}
class InputTest extends PHPUnit_Framework_TestCase
{
    public $input;

    public function setUp()
    {
        require_once '../core/lib/Input.php';
        $this->input = new Input('development');

    }

    public function tearDown() {

    }


    public function testConstructor()
    {
        $this->assertTrue(is_array($this->input->server));
        $return = $this->input;
        $this->assertObjectHasAttribute('server', $return);
        $this->assertObjectHasAttribute('cookie', $return);
        $this->assertObjectHasAttribute('get', $return);
        $this->assertObjectHasAttribute('post', $return);
        $this->assertObjectHasAttribute('session', $return);
    }

    public function testLoadSuperglobals() {
        $return = $this->input->loadSuperglobals();
        $this->assertObjectHasAttribute('server', $return);
        $this->assertObjectHasAttribute('cookie', $return);
        $this->assertObjectHasAttribute('get', $return);
        $this->assertObjectHasAttribute('post', $return);
        $this->assertObjectHasAttribute('session', $return);
    }

    public function testSetSessionVariable()
    {
        $return = $this->input->session('test','test123');
        $this->assertEquals('test123', $_SESSION['test']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetCookieVariable()
    {
        ob_start();
        $return = $this->input->cookie('test','test123');
        $this->assertTrue($return);
        ob_end_flush();
    }

}