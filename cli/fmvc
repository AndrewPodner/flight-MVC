#!/usr/bin/env php
<?php
/**
 * File Description:
 *
 * Primary Command Line Tool
 *
 * Arugement 1 must always be the command
 *
 * @category
 * @package
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2014
 * @license    /license.txt
 */



//assure running from command line
if (php_sapi_name() !== 'cli') {
    die("Error 403: Access Forbidden! \r\n");
}

// Get any arguements passed in.

$args = array();
$args = $_SERVER['argv'];

$command = $args[1];
$param1 = $args[2];


switch ($command)
{
    /**
     * Command line function for starting/stopping the app
     * or also changing the environment (e.g. development,
     * testing, etc.)
     *
     */
    case 'environment':
        try {
            require_once 'lib/Environment.php';
            $env = new \fmvc\cli\lib\Environment($param1, '../config/environment.php');
            switch ($param1)
            {
                case 'start':
                case 'halt':
                    $result = $env->toggleHalt();
                    break;

                default:
                    $result= $env->toggleEnvironment();
                    break;
            }
            if ($result === true) {
                echo "\r\nSuccess: site is in $param1 mode \r\n";
            } else {
                throw new \Exception("ERROR: environment mode not updated! \r\n");
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        break;

    /**
     *  Construct a model, view, or controller class file and save it
     */
    case 'model':
    case 'controller':
    case 'view':
        try {
            require_once 'lib/Filegen.php';
            $mod = new \fmvc\cli\lib\Filegen();
            $applicationPath = "..".DIRECTORY_SEPARATOR."application".DIRECTORY_SEPARATOR.$command.DIRECTORY_SEPARATOR;
            $templatePath = ".".DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR;
            $objectName = $param1;
            $result = $mod->createObject($command, $objectName, $applicationPath, $templatePath);
            if ($result === true) {
                echo "New $command created: $objectName\r\n";
            } else {
                throw new \Exception('CLI ERROR: File Generation or Creation Error');
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        break;

    default:
        echo "CLI ERROR: The `$command` command was not found\r\n";
        break;
}
