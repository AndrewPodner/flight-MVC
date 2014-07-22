<?php
/**
 * File Description:
 *
 * NOTE:  YOU NEED TO SET BASEURL TO WHATEVER THE SERVER YOU ARE TESTING FROM IS
 *
 * @category
 * @package
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2014
 * @license    /license.txt
 */
use fmvc\core\helper\RestApiCaller;

class RestApiCallerTest extends PHPUnit_Framework_TestCase
{
    public $baseUrl;


    public function setUp()
    {
        require_once '../core/helper/RestApiCaller.php';
        $this->baseUrl = 'http://localhost:8888/flight-MVC/tests/api_test/' ;
    }

    public function testGet()
    {
        $url =  $this->baseUrl . 'get.php';
        $data = RestApiCaller::get($url);
        $this->assertEquals('Testing Get Call', $data);
    }

    public function testPostFields()
    {
        $url = $this->baseUrl . 'post.php';
        $data = array('a' => 'Foo', 'b' => 'Bar');
        $type = 'fields';

        $this->assertEquals('Bar', RestApiCaller::post($url, $data, $type));
    }

    public function testPostJson()
    {
        $url = $this->baseUrl . 'post-json.php';
        $data = array('a' => 'Foo', 'b' => 'Bar');
        $type = 'json';

        $result =  json_decode(RestApiCaller::post($url,$data,$type));
        $this->assertEquals('Bar', $result->b);
    }

    public function testPostXml()
    {
        $url = $this->baseUrl . 'post-xml.php';
        $data = 'Some text to test';
        $type = 'xml';

        $result =  RestApiCaller::post($url,$data,$type);
        $this->assertEquals('Some text to test', $result);
    }

    public function testPutJson()
    {
        $url = $this->baseUrl . 'put.php';
        $data = array('a' => 'Foo', 'b' => 'Bar');
        $type = 'json';
        $result =  json_decode(RestApiCaller::put($url,$data,$type));

        $this->assertEquals('Bar', $result->b);
    }

    public function testDelete()
    {
        $url = $this->baseUrl . 'delete.php';
        $result =  RestApiCaller::delete($url);
        $this->assertEquals('Working', $result);
    }
}
