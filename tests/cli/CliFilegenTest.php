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
class CliFilegenTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        require_once '../cli/lib/Filegen.php';
    }

    public function tearDown()
    {
        if (file_exists('temp/test.php')) unlink('temp/test.php');
        if (file_exists('temp/my'))  rmdir('temp/my');
    }

    public function testCreateObject()
    {
        $fgen = new \fmvc\cli\lib\Filegen();
        $result = $fgen->createObject('controller', 'test', 'temp/', '../cli/templates/');
        $text = file_get_contents('temp/Test.php');
        $test = (stristr($text, 'class Test extends \fmvc\core\lib\Controller') !== false) ? true : false;
        $this->assertFileExists('temp/Test.php');
        unlink('temp/Test.php');
        $this->assertTrue($test);
    }

    public function testCreateObjectWithSubdir()
    {
        $fgen = new \fmvc\cli\lib\Filegen();
        $result = $fgen->createObject('controller', 'my/test', 'temp/', '../cli/templates/');
        $text = file_get_contents('temp/my/Test.php');
        $test = (stristr($text, 'class Test extends \fmvc\core\lib\Controller') !== false) ? true : false;
        $this->assertFileExists('temp/my/Test.php');
        unlink('temp/my/Test.php');
        rmdir('temp/my');
        $this->assertTrue($test);
    }

    public function testCreateView()
    {
        $fgen = new \fmvc\cli\lib\Filegen();
        $result = $fgen->createObject('view', 'test', 'temp/', '../cli/templates/');
        $text = file_get_contents('temp/test.php');
        $test = (stristr($text, '<html>') !== false) ? true : false;
        $this->assertFileExists('temp/test.php');
        unlink('temp/test.php');
        $this->assertTrue($test);
    }

    /**
     * @expectedException Exception
     */
    public function testCreateObjectFailAlreadyExists()
    {
        file_put_contents('temp/test.php', 'test 123');
        $fgen = new \fmvc\cli\lib\Filegen();
        $result = $fgen->createObject('view', 'test', 'temp/', '../cli/templates/');
    }

    /**
     * @expectedException Exception
     */
    public function testCreateWriteFileFailure()
    {
        mkdir('temp/my');
        chmod('temp/my', 0000);
        $fgen = new \fmvc\cli\lib\Filegen();
        $result = $fgen->createFile('temp/my', null, null);
    }

}
