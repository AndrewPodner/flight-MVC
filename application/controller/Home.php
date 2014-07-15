<?php
/**
 * File Description: Home (default) controller
 *
 * @category   controller
 * @package    application
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2014
 * @license    /license.txt
 */
namespace fmvc\application\controller;

class Home extends \fmvc\core\lib\Controller
{
    /**
     * Constructor: Dependencies are picked up from routes.php  The default
     * Dependencies sent to the controller are Config and Input, which are then
     * passed through to the parent constructor
     * @param array $deps dependencies
     * @param array $params parameters passed via the uri
     */
    public function __construct($deps, $params)
    {
        parent::__construct($deps, $params);

    }

    /**
     * Main Menu, Home Page
     */
    public function index()
    {

        \Flight::render('home/index', array('html_head' => $this->head->head()));
    }
}
