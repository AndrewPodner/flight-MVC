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
require_once '../core/helper/Documentation.php';

use fmvc\core\helper\Documentation;

class DocumentationTest extends PHPUnit_Framework_TestCase
{


    /**
     * Mock of the Parsedown Object
     * @var
     */
    public $parse;


    public function setUp()
    {
        $this->parse = $this->getMockBuilder('Parsedown')
            ->disableOriginalConstructor()
            ->setMethods(array('text'))
            ->getMock();


    }

    public function tearDown()
    {

    }

    public function testConstructor()
    {
       $doc = new Documentation('testVal');
       $this->assertEquals('testVal', $doc->dirName);
    }

    public function testConstructorNull()
    {
        $doc = new Documentation();
        $this->assertEquals('./docs', $doc->dirName);
    }

    public function testGetDocumentationFilenames()
    {
        $doc = new Documentation('../docs');
        $array = $doc->getDocumentationFileNames();
        $this->assertArrayHasKey(1,$array);
    }

    public function testParseDocumentationFile()
    {
        $doc = new Documentation('../temp');

        $this->parse->expects($this->once())
            ->method('text')
            ->will($this->returnValue('<h1>test123</h1>'));

        file_put_contents('../temp/test.md', '#test123');
        $result = $doc->parseDocumentationFile('test.md', $this->parse);
        if (file_exists('../temp/test.md')) {
            unlink('../temp/test.md');
        }
    }

}