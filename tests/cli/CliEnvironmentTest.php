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


class CliEnvironmentTest extends PHPUnit_Framework_TestCase
{
    public $config;

    public function setUp()
    {
        require_once '../cli/lib/Environment.php';
    }

    public function tearDown() {}

    /**
     * @expectedException Exception
     */
    public function testNullConstructor() {
        $test = new \fmvc\cli\lib\Environment();
    }

    /**
     * @expectedException Exception
     */
    public function testNullConstructor2() {
        $test = new \fmvc\cli\lib\Environment('test');
    }

    public function testConstructor()
    {
        $test = new \fmvc\cli\lib\Environment('test', 'file.php');
        $this->assertEquals('test', $test->mode);
        $this->assertEquals('file.php', $test->fileName);
    }

    public function testWriteFile()
    {
        $file = 'temp/testwrite.txt';
        if (file_exists($file)) {
            unlink($file);
        }
        $env = new \fmvc\cli\lib\Environment('test', $file);

        file_put_contents($file, 'Test data');
        $this->assertTrue($env->writeFile('my test data'));

        $newData = file_get_contents($file);
        $this->assertEquals('my test data', $newData);
        unlink($file);
    }

    public function testWriteFileFail()
    {
        $file = 'temp/testwrite.txt';
        if (file_exists($file)) {
            unlink($file);
        }
        file_put_contents($file, 'Test data');
        $env = new \fmvc\cli\lib\Environment('test', $file);
        $this->assertFalse($env->writeFile(''));
    }

    public function testHalt()
    {
        $file = 'temp/testwrite.txt';
        if (file_exists($file)) {
            unlink($file);
        }
        file_put_contents($file, 'Test data');
        $env = new \fmvc\cli\lib\Environment('halt', $file);
        $this->assertTrue($env->toggleHalt());

        $data = file_get_contents($file);
        $this->assertStringStartsWith("<?php die" , $data);
        unlink($file);
    }

    public function testToggleHaltFail()
    {
        $file = 'temp/testwrite.txt';
        if (file_exists($file)) {
            unlink($file);
        }
        $env = new \fmvc\cli\lib\Environment('development', $file);
        $this->assertFalse($env->toggleHalt());
    }
    public function testStart()
    {
        $file = 'temp/testwrite.txt';
        if (file_exists($file)) {
            unlink($file);
        }
        file_put_contents($file, 'Test data');
        $env = new \fmvc\cli\lib\Environment('start', $file);
        $this->assertTrue($env->toggleHalt());

        $data = file_get_contents($file);
        $this->assertEquals("<?php\r\n" , $data);
        unlink($file);
    }

    public function testEnvironment()
    {
        $file = 'temp/testwrite.txt';
        if (file_exists($file)) {
            unlink($file);
        }
        file_put_contents($file, '');
        $env = new \fmvc\cli\lib\Environment('development', $file);
        $this->assertTrue($env->toggleEnvironment());

        $data = file_get_contents($file);
        $this->assertEquals("define('APP_ENVIRONMENT', 'development');\r\n", $data);
        unlink($file);
    }

    public function testToggleEnvironmentFail()
    {
        $file = 'temp/testwrite.txt';
        if (file_exists($file)) {
            unlink($file);
        }
        $env = new \fmvc\cli\lib\Environment('start', $file);
        $this->assertFalse($env->toggleEnvironment());
    }

    public function testAutoDetect()
    {
        $file = 'temp/testwrite.txt';
        if (file_exists($file)) {
            unlink($file);
        }
        file_put_contents($file, '');
        $env = new \fmvc\cli\lib\Environment('auto', $file);
        $this->assertTrue($env->toggleEnvironment());

        $data = file_get_contents($file);
        $this->assertEquals("define('APP_ENVIRONMENT', null);\r\n", $data);
        unlink($file);
    }
}
