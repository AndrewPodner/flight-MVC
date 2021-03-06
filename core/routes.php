<?php
/**
 * File Description:
 *
 * Application Route Definitions
 *
 * Sets up a route to implement a traditional MVC Controller feature
 * into the Flight Micro-framework. This router will accept the
 * controller, the command, and then 2 parameters/arguments
 *
 * e.g. `http://example.com/controller/command/param1/param2`
 *
 * @category   none
 * @package    core
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2014
 * @license    /license.txt
 */

// Remap the not found function
Flight::map('notFound', function () {
    Flight::render('404.php');
});
//Use Windows Authentication to assure that there is
//a legitimate user logged into the browser.  App depends on Windows Auth via IIS
//If we find an AUTH_USER in the IE Server Variable, set it as a configuration item
//If not, render a 401 error

if (Flight::config()->item('use_windows_auth') == true) {
    if (!isset(Flight::input()->server['AUTH_USER'])
        or is_null(Flight::input()->server['AUTH_USER'])
        or Flight::input()->server['AUTH_USER'] == '') {
        Flight::render('401.php');
        Flight::stop();
    } else {
        $t_user = strtoupper(str_replace(Flight::config()->item('auth_domain'), '', Flight::input()->server['AUTH_USER']));
        Flight::config()->set('auth_user', $t_user);
        //process any additional user authentication here (e.g. load your user class

    }
}

// Set up a route handler for Application Controllers
// Route accepts a controller, command and 2 arguments
Flight::route('(/@controller(/@command(/@arg1(/@arg2))))', function ($controller, $command, $arg1, $arg2) {
    // Set `home` as the default controller
    if (! isset($controller)) {
        $controller = 'home';
    }

    // Set `index` as the default route
    if (! isset($command)) {
        $command = 'index';
    }

    // Set the controller name and command as config variables
    \Flight::config()->set('controller', $controller);
    \Flight::config()->set('command', $command);

    // Build a fully qualified controller name
    $fqController = 'fmvc\application\controller\\' . ucfirst($controller);

    try {
        // Put parameters into array
        $arrParam = array($arg1, $arg2);

        // Designate Dependencies to Pass into Controller
        //  ** Always make the Config and Input classes
        //  ** available to the controller
        $arrDep = array(
            'config' => Flight::config(),
            'input' => Flight::input(),
            'head' => Flight::head()
        );

        // Check for the controller, if it doesn't exist, go to 404
        if (! class_exists($fqController)) {
            Flight::render('404.php');
            Flight::stop();
        }

        // Load the controller & call the designated method
        $ctrl = new $fqController($arrDep, $arrParam);

        // If someone tries to call a private or protected controller method...
        $reflection = new \ReflectionMethod($ctrl, $command);
        if (! $reflection->isPublic()) {
            Flight::render('404.php');
            Flight::stop();
        }

        // Return a 404 error if the command is not valid
        if (! method_exists($fqController, $command)) {
            Flight::render('404.php');
        } else {
                $ctrl->$command();
        }

    } catch (\Exception $e) {
        echo $e->getMessage();
    }
});


// Catch all route for undefined routes in case the request
// gets past the router above
Flight::route('*', function () {
    Flight::render('404.php');
});
