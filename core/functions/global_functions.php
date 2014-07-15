<?php
/**
 * File Description:  functions that are global to
 * the application
 *
 * @category   core
 * @package    functions
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2014
 * @license    /license.txt
 */

if (! function_exists('debug')) {
    /**
     * Debugger Function: echoes the provided information in an easier
     * to read format on screen
     *
     * @param string $input
     */
    function debug($input)
    {
        echo '<pre>' . print_r($input, true) . '</pre>';
    }
}

if (! function_exists('setEnvironment')) {
    /**
     * Manages the running mode of the app.  There is an auto-detect feature
     * that sets the mode to development if it detects that the server is
     * 127.0.0.1, localhost, or the domain is .dev or .local
     *
     * The auto-detect will go to testing otherwise.  It will never automatically
     * put the site into production mode.
     *
     * The auto-detect feature can be overridden by specifying the desired run mode.
     *
     * @param null $mode
     * @return null|string
     *
     * @TODO: set up a way to put in User defined configurations (servers, etc)
     * @TODO: fix the hacky double foreach....
     */

    function setEnvironment($mode = null)
    {
        $runMode = 'testing';
        $arrVars = array(
            $_SERVER['HTTP_HOST'],
            $_SERVER['SERVER_NAME'],
            $_SERVER['SERVER_ADDR'],
            $_SERVER['REMOTE_ADDR']
        );

        $arrLocal = array(
            '127.0.0.1',
            '.dev',
            '.local',
            'localhost'
        );

        if (is_null($mode)) {
            echo array_search($arrLocal, $arrVars);
            foreach ($arrVars as $key => $value) {
                foreach ($arrLocal as $srchKey => $srchValue) {
                    if (stristr($value, $srchValue)) {
                        return 'development';
                    }
                }
            }

        // Set the Environment as specified if a value is passed
        } else {
            $runMode = $mode;
        }
        return $runMode;
    }
}
