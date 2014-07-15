<?php
/**
 * File Description:
 *
 * Error and Event Logging Class that writes out to a log file
 * in the configured directory.  Each level of logging gets its
 * own file in the directory.
 *
 * Also contains a __call() method that allows the logging level to
 * be used as the method name.
 *
 * Requires the configuration dependency
 *
 * @category   helper
 * @package    core
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2013
 * @license    /license.txt
 *
 * @todo: Provide a configurable option to write log to SQLite database
 * @todo: Maybe add an API to read the logs into JSON or something like that.
 * @todo: Look at adapting this Logger to PSR-3
 *
 *
 */
namespace fmvc\core\helper;

class Logger
{
    /**
     * Configuration Dependency
     * @var Config
     */
    public $config;

    /**
     * Types of logging events, add to this as desired
     * @var array
     */
    public $levels = array(
        'event',
        'notice',
        'warning',
        'error',
    );

    public function __construct(array $arrDep = array())
    {
        if (empty($arrDep)) {
            throw new \Exception('Dependency Failure');
        } else {
            foreach ($arrDep as $key => $object) {
                $this->$key = $object;
            }
        }
    }

    /**
     * Dynamic method caller that will allow you to use the log level as
     * the method name
     *
     * e.g.  $log->notice($message, $data)
     *
     * @param string $method name of the method which will map to the event level
     * @param array $arrParams
     */
    public function __call($method, $arrParams)
    {
        $this->logEvent($method, $arrParams[0], $arrParams[1]);
    }

    /**
     * Logs an event and writes it to the appropriate log file
     *
     * @param null|string $level log level
     * @param null|string $message human readable message
     * @param null|mixed $data additional data
     * @return int|bool  number of bytes on success, false on failure
     * @throws \Exception
     */
    public function logEvent($level = null, $message = null, $data = null)
    {
        // First 2 parameters are required, so fail if one is missing
        if (is_null($level) || is_null($message)) {
            throw new \Exception('Level and Log Message are Both Required');
        } else {

            // Make sure this is a valid message level
            $this->checkLevelIsValid($level);

            // Compose the Log Message
            $logMessage  = "----Logged $level: ".date('m-d-Y h:m:sa', time())."----- \n";
            $logMessage .= "Message Content : [$message]\n";
            $logMessage .= "Additional Data : " . $data . "\n";

            // Open or Create the Log File, named whatever the event level is
            $file = $this->config->item('error_log_path').$level.'.txt';
            $fh = @fopen($file, 'a');
            if (! file_exists($file) || ! $fh || ! is_writable($file)) {
                // Throw an exception if we cannot get a file handle
                throw new \Exception('Log file cannot be opened or created');
            }

            return fwrite($fh, $logMessage);
        }
    }

    /**
     * Check to see if the log level is valid
     *
     * @param null|string $level
     * @throws \Exception
     */
    public function checkLevelIsValid($level = null)
    {
        if (! in_array($level, $this->levels) || is_null($level)) {
            throw new \Exception('Log Level is invalid');
        }
    }
}
