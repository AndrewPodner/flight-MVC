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
use \fmvc\core\data\PdoConn;
use \fmvc\core\lib\Config;
require_once '../core/data/PdoConn.php';
require_once '../core/lib/Config.php';

class PdoConnTest extends PHPUnit_Framework_TestCase
{
    public $db;
    public $conf;
    public $insert_id;

    public function setUp()
    {
        $config['db_default']['db_path'] = '../data/sqlite';
        $config['db_default']['db_filename'] = 'unit_test.db';
        $config['db_default']['driver'] = 'sqlite';
        $config['db_prefix'] = '';
        $this->conf = new Config($config);

        $this->db = new PdoConn(array('config' => $this->conf));
    }

    public function tearDown()
    {
        $this->db = null;
    }


    public function testMysqlConnection()
    {
        $this->assertObjectHasAttribute('conn', $this->db);
    }

    /**
     * @expectedException \Exception
     */
    public function testSqlServerConnectionAndConnFailure()
    {
        $config2['db_default']['dsn'] = 'localhost:1433';
        $config2['db_default']['db_user'] = 'user';
        $config2['db_default']['db_password'] = 'sample';
        $config2['db_default']['driver'] = 'sqlsrv';
        $config2['db_prefix'] = '';
        $conf2 = new Config($config2);
        $db3 = new PdoConn(array('config'=>$conf2));
        $db3 = null;
    }

    /**
     * @expectedException \Exception
     */
    public function testOdbcConnectionAndConnFailure()
    {
        $config2['db_default']['dsn'] = 'localhost:9999';
        $config2['db_default']['db_user'] = 'user';
        $config2['db_default']['db_password'] = 'sample';
        $config2['db_default']['driver'] = 'odbc';
        $config2['db_prefix'] = '';
        $conf2 = new Config($config2);
        $db3 = new PdoConn(array('config'=>$conf2));
        $db3 = null;
    }

    /**
     * @expectedException \Exception
     */
    public function testMysqlConnectionAndConnFailure()
    {
        $config2['db_default']['host'] = 'localhost';
        $config2['db_default']['port'] = '8889';
        $config2['db_default']['db_user'] = 'root';
        $config2['db_default']['db_name'] = 'test';
        $config2['db_default']['db_password'] = 'mysql2';
        $config2['db_default']['driver'] = 'mysql';
        $config2['db_prefix'] = 'pims_';
        $conf2 = new Config($config2);
        $db3 = new PdoConn(array('config'=>$conf2));
        $db3 = null;
    }

    /**
     * @expectedException \Exception
     */
    public function testPostgresConnectionAndConnFailure()
    {
        $config2['db_default']['db_user'] = 'root';
        $config2['db_default']['db_name'] = 'test';
        $config2['db_default']['db_password'] = 'mysql2';
        $config2['db_default']['port'] ='1234';
        $config2['db_default']['host'] = 'localhost';
        $config2['db_default']['driver'] = 'pgsql';
        $conf2 = new Config($config2);
        $db3 = new PdoConn(array('config'=>$conf2));
        $db3 = null;
    }

    /**
     * @runInSeparateProcess
     * @expectedException \Exception
     */
    public function testPostgresPortConnectionAndConnFailure()
    {
        $config2['db_default']['db_user'] = 'root';
        $config2['db_default']['db_name'] = 'test';
        $config2['db_default']['port'] = '5444';
        $config2['db_default']['db_password'] = 'mysql2';
        $config2['db_default']['driver'] = 'pgsql';
        $conf2 = new Config($config2);
        $db3 = new PdoConn(array('config'=>$conf2));
        $db3 = null;
    }


    /**
     * @expectedException \Exception
     */
    public function testSqliteConnectionAndConnFailure()
    {
        $config2['db_default']['db_path'] = null;
        $config2['db_default']['driver'] = 'sqlite';
        $conf2 = new Config($config2);
        $db3 = new PdoConn(array('config'=>$conf2));
        $db3 = null;
    }

    /**
     * @expectedException \Exception
     */
    public function testConnectionDependencyFailure()
    {
        $db = new PdoConn(array());
    }

    /**
     *
     */
    public function testConnectionWithName()
    {
        $db2 = new PdoConn(array('config' => $this->conf), 'default');
        $this->assertObjectHasAttribute('conn', $db2);
        $db2 = null;
    }


    /**
     *
     */
    public function testInit()
    {
        $db2 = new PdoConn(array('config' => $this->conf), 'default');
        $db2->init();
        $this->assertObjectHasAttribute('conn', $db2);
        $db2 = null;
    }

    public function testOverloadGet()
    {
        $arrOutput = $this->db->getCompaniesById(1);
        $this->assertArrayHasKey('abbr', $arrOutput[0]);
    }


    /**
     * @expectedException \PDOException
     */
    public function testOverloadGetFail()
    {
        $arrOutput = $this->db->getCompaniesByIx(1);
        //$this->assertArrayHasKey('abbr', $arrOutput[0]);
    }

    public function testOverloadFiler()
    {
        $arrOutput = $this->db->filterCompaniesById(1);
        $this->assertArrayHasKey('abbr', $arrOutput[0]);
    }
    public function testFilterSort()
    {
        $arrOutput = $this->db->filter('companies', 'id', '1', 'abbr');
        $this->assertArrayHasKey('abbr', $arrOutput[0]);
    }


    public function testFilter()
    {
        $arrOutput = $this->db->filter('companies', 'id', 1);
        $this->assertArrayHasKey('abbr', $arrOutput[0]);

        $arrOutput = $this->db->filter('companies', 'id', '1*');
        $this->assertArrayHasKey('abbr', $arrOutput[0]);


    }

    /**
     * @expectedException \PDOException
     */
    public function testFilterFail()
    {
        $arrOutput = $this->db->filter('companies', 'ix', 1);
        //$this->assertArrayHasKey('abbr', $arrOutput[0]);
    }

    public function testOverloadInsert()
    {
        $arr = array(
            'abbr' => "TST",
            'descr' => "TEST COMPANY",
        );
        $result = $this->db->insertCompanies($arr);
        $this->insert_id = $result;
        $rs = $this->db->getCompaniesById($result);
        $this->assertArrayHasKey('abbr', $rs[0]);
        $this->assertEquals('TST', $rs[0]['abbr']);
    }

    /**
     * @expectedException \PDOException
     */
    public function testOverloadInsertFail()
    {
        $arr = array(
            'abbrev' => "TST",
            'descr' => "TEST COMPANY",
        );
        $result = $this->db->insertCompanies($arr);

    }

    public function testOverloadUpdate()
    {

        $arr = array('abbr' => 'TTS');
        $this->db->updateCompaniesByAbbr('TST' , $arr);
        $rs = $this->db->getCompaniesByAbbr('TTS');
        $this->assertArrayHasKey('abbr', $rs[0]);
        $this->assertEquals('TTS', $rs[0]['abbr']);
    }

    /**
     * @expectedException \PDOException
     */
    public function testOverloadUpdateFail()
    {
        $arr = array('abbrev' => 'TTS');
        $this->db->updateCompaniesById('TST', $arr);
    }

    public function testOverloadDelete()
    {

        $rs = $this->db->deleteCompaniesByAbbr('TTS');
        $this->assertEquals(1, $rs);
    }

    /**
     * @expectedException \PDOException
     */
    public function testOverloadDeleteFail()
    {
        $rs = $this->db->deleteCompaniesByIx('TTS');
    }

    public function testOverloadGetAll()
    {
        $rs = $this->db->allCompanies();
        $this->assertArrayHasKey('abbr', $rs[0]);
    }

    /**
     * @expectedException \PDOException
     */
    public function testOverloadGetAllFail()
    {
        $rs = $this->db->allCompaniess();
    }


    public function testCamelCaseToUnderscore()
    {
        $val = 'SomeTest';
        $expected = 'some_test';
        $ret = $this->db->camelCaseToUnderscore($val);
        $this->assertEquals($expected, $ret);
    }


}
