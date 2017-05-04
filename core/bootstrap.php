<?php
/**
 * File Description: Application Bootstrap
 * to get the app initialized and running
 *
 * In this file we will actually start configuring the application
 * and registering classes, implementing config settings, etc.
 *
 * @category   none
 * @package    core
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2014
 * @license    /license.txt
 */
// Initialize a PHP Session
if (SESSIONS_ENABLED === true) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

// Set up the Views Directory
Flight::set('flight.views.path', 'application/view');

// Register the Configuration Dependency
Flight::register('config', 'fmvc\core\lib\Config', array($config));

// Put the Running Mode into a Config Variable
Flight::config()->set('environment', setEnvironment(APP_ENVIRONMENT));

// Register the Superglobals into an accessible container
Flight::register('input', 'fmvc\core\lib\Input', array());

// Register the Logger Dependency
Flight::register('log', 'fmvc\core\helper\Logger', array(array('config' => Flight::config())));

// Register the HTML Head Dependency
Flight::register('head', 'fmvc\core\helper\HtmlHead', array(array('config' => Flight::config())));

// Establish the default database connection
Flight::register('db', 'fmvc\core\data\PdoConn', array(array('config' => Flight::config())));

// Initialize Application Routing
require_once 'routes.php';
