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

date_default_timezone_set('America/New_York');
use \fmvc\core\helper\Logger;
require_once '../core/helper/Logger.php';
require_once '../core/lib/Config.php';

class LoggerTest extends PHPUnit_Framework_TestCase
{
    public $log;
    public $conf;

    public function setUp()
    {
        $config['error_log_path'] = 'temp/';
        $this->conf = new \fmvc\core\lib\Config($config);
        $this->log = new Logger(array('config' => $this->conf));
    }

    public function tearDown()
    {
        //exec('mv ./temp1 ./temp');
        //exec('chmod 0777 ./temp');
    }

    /**
     * @expectedException \Exception
     */
    public function testLoggerConstructorFail()
    {
        $log = new Logger();
    }


    public function testLogger()
    {
        if ( ! is_writeable('./temp/notice.txt')) {
            exec("chmod 777 ./temp/notice.txt");
        }
        $this->log->notice('Test 123 Notice', 'Some Data');

        $file = file_get_contents('temp/notice.txt');
        $this->assertFileExists('temp/notice.txt');
        $this->assertStringStartsWith('----Logged', $file);
        //unlink('temp/notice.txt');

    }

    /**
     * @expectedException \Exception
     */
    public function testLoggerFail()
    {
        if ( ! is_writeable('./temp/notice.txt')) {
            exec("chmod 777 ./temp/notice.txt");
        }
        $this->log->notice(null, null);

        $file = file_get_contents('temp/notice.txt');
        $this->assertFileExists('temp/notice.txt');
        $this->assertStringStartsWith('----Logged', $file);
        //unlink('temp/notice.txt');

    }

    /**
     * @expectedException \Exception
     */
    public function testLoggerFailMethodName()
    {
        $this->log->notic('Test 123 Notice', 'Some Data');
    }

    /**
     * @expectedException \Exception
     */
    public function testLoggerFailParams()
    {
        $this->log->notice();
    }

    /**
     * @expectedException \Exception
     */
    public function testFileOpenFail()
    {
        $this->log->levels = array_merge($this->log->levels, array('hello/hello'));
        $this->log->logEvent('hello/hello','test', 'test');
    }

    /**
     * @expectedException \Exception
     */
    public function testFileWriteFail()
    {

        if (is_writeable('./temp/notice.txt')) {
            exec("chmod 444 ./temp/notice.txt");
        }
        $this->log->logEvent('notice', 'test', 'test');
    }


}
