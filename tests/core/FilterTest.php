<?php
date_default_timezone_set('America/New_York');
use \fmvc\core\helper\Filter;
class FilterTest extends PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        require_once '../core/helper/Filter.php';
    }

    protected function tearDown()
    {

    }

    public function testFilename()
    {
        $good = array(
            'config_test.php',
            'config.html',
            'config.xls',
            'config.pdf'
        );

        foreach($good as $val)
        {
            $this->assertTrue(Filter::filename($val));
        }

        $bad = array(
            'config-test.php',
            'co(fig.php',
            'me@example.com'
        );

        foreach($bad as $badVal)
        {
            $this->assertFalse(Filter::filename($badVal));
        }


    }

    public function testInteger()
    {
        $vals = array(1,2354,6543,2,24);
        $badVals = array('x','ABCD', 1.5, '1.33');

        foreach ($vals as $val)
        {
            $this->assertTrue(Filter::integer($val),"Tested $val for Integer Filter");
        }

       foreach ($badVals as $bad) {
           $this->assertFalse(Filter::integer($bad), "Tested $bad for Numeric Fail Filter");
       }
    }

    public function testNumeric()
    {
        $vals = array(1,2354,6543,2,24.6,1.2354);
        $badVals = array('x','ABCD','1.33z');

        foreach ($vals as $val)
        {
            $this->assertTrue(Filter::numeric($val), "Tested $val for Numeric Filter");
        }

        foreach ($badVals as $bad) {
            $this->assertFalse(Filter::numeric($bad), "Tested $bad for Numeric Fail Filter");
        }
    }

    public function testAlphanumeric()
    {
        $vals = array(2354,6543,'2abcs', 'mnlkkjdsa', 'asdejfjaASDEV1234');
        $badVals = array('space Val','ABCD@','1.33z', 'test-value', 'test_value');

        foreach ($vals as $val)
        {
            $this->assertTrue(Filter::alphanumeric($val), "Tested $val for Alphanumeric Filter");
        }

        foreach ($badVals as $bad) {
            $this->assertFalse(Filter::alphanumeric($bad), "Tested $bad for Numeric Fail Filter");
        }
    }

    public function testAlpha()
    {
        $vals = array('abcs', 'mnlkkjdsa', 'asdejfjaASDEV');
        $badVals = array('space Val','ABCD@','1.33z', 'test-value', 'test_value');

        foreach ($vals as $val)
        {
            $this->assertTrue(Filter::alpha($val), "Tested $val for Alpha Filter");
        }

        foreach ($badVals as $bad) {
            $this->assertFalse(Filter::alpha($bad), "Tested $bad for Alpha Fail Filter");
        }
    }


    public function testEmail()
    {
        $vals = array('test@mail.test.com', 'p.diddy@example.net', 'joe_blow@test.org');
        $badVals = array('www.test.com','joe@example','tim#excampl.com');

        foreach ($vals as $val)
        {
            $this->assertTrue(Filter::email($val), "Tested $val for Email Filter");
        }

        foreach ($badVals as $bad) {
            $this->assertFalse(Filter::email($bad), "Tested $bad for Email Fail Filter");
        }
    }

    public function testBoolean()
    {
        $vals = array(true, 1, 'on', 'yes');
        $badVals = array('www.test.com', 0, null, false);

        foreach ($vals as $val)
        {
            $this->assertTrue(Filter::boolean($val), "Tested $val for Boolean Filter");
        }

        foreach ($badVals as $bad) {
            $this->assertFalse(Filter::boolean($bad), "Tested $bad for Boolean Fail Filter");
        }
    }


    public function testPageName()
    {
        $vals = array('test', 'testPage1', 'test_page');
        $badVals = array('test-page', 'test.page', 'test page');

        foreach ($vals as $val)
        {
            $this->assertTrue(Filter::pageName($val), "Tested $val for PageName Filter");
        }

        foreach ($badVals as $bad) {
            $this->assertFalse(Filter::pageName($bad), "Tested $bad for PageName Fail Filter");
        }
    }

    public function testDatabaseField()
    {
        $vals = array('test', 'testPage1', 'test_page', 'test.page');
        $badVals = array('test-page', 'test page', 'test%page');

        foreach ($vals as $val)
        {
            $this->assertTrue(Filter::databaseField($val), "Tested $val for Field Filter");
        }

        foreach ($badVals as $bad) {
            $this->assertFalse(Filter::databaseField($bad), "Tested $bad for Field Fail Filter");
        }
    }

    public function testCheckDateIsValid()
    {
        $vals = array('1/12/15', '1/1/15', '01/02/2015', '12/25/2015', '12/16/14');
        $badVals = array('1/32/2013', '13/1/1999', '2/29/1999', '11/10/234015', '1/15');

        foreach ($vals as $val)
        {
            $this->assertTrue(Filter::checkDateIsValid($val), "Tested $val for Date Filter");
        }

        foreach ($badVals as $bad) {
            $this->assertFalse(Filter::checkDateIsValid($bad), "Tested $bad for Date Fail Filter");
        }
    }

}
