<?php
/**
 * File Description: Application front controller
 *
 * In this file we will just include the files we need to
 * run the app
 *
 * @category   main
 * @package    app
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2014
 * @license    /license.txt
 */

// Load the environment configuration file
require_once dirname(__DIR__) . '/config/environment.php';

// include autoloader files
require_once dirname(__DIR__) . '/libraries/autoload.php';

// include global functions
require_once dirname(__DIR__) . '/core/functions/global_functions.php';

// Load the environment based configuration
require_once dirname(__DIR__) . '/config/' .setEnvironment(APP_ENVIRONMENT). '.php';

// load the bootstrap file
require_once dirname(__DIR__) . '/core/bootstrap.php';

// here we go!
Flight::start();