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
namespace fmvc\application\controller;

class Debug extends \fmvc\core\lib\Controller
{
    public function __construct($deps, $params)
    {
        parent::__construct($deps, $params);
    }

    /**
     * Not used, show 404 error
     */
    public function index()
    {

        \Flight::render('404', array());
    }

    /**
     * Show PHP Info, but not in production environment
     * DELETE THIS METHOD IF YOU DON'T NEED IT
     */
    public function info()
    {
        echo \Flight::config()->item('environment');
        if (\Flight::config()->item('environment') !== 'production') {
            echo phpinfo();
        } else {
            echo 'DISABLED';
        }
    }

    /**
     * Debug dump of the Controller Object (not in production)
     * DELETE THIS METHOD IF YOU DON'T NEED IT
     */
    public function this()
    {
        echo  "Current Environment: " . \Flight::config()->item('environment') . "<br />";
        if (\Flight::config()->item('environment') !== 'production') {
            echo "<h2>Debug of the current controller object</h2>";
            debug($this);
        } else {
            echo 'DISABLED';
        }
    }

    /**
     * Debug dump of the Config Object (not in production)
     * DELETE THIS METHOD IF YOU DON'T NEED IT
     */
    public function config()
    {
        echo  "Current Environment: " . \Flight::config()->item('environment') . "<br />";
        if (\Flight::config()->item('environment') !== 'production') {
            echo "<h2>Debug of the current configuration object</h2>";
            debug(\Flight::config()->item());
        } else {
            echo 'DISABLED';
        }
    }
}
