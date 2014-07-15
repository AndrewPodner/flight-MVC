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
namespace fmvc\cli\lib;

class Environment
{
    public $fileName;

    public $mode;

    public function __construct($mode = null, $filename = null)
    {
        if (is_null($mode) || is_null($filename)) {
            throw new \Exception("Mode and Filename are required. \r\n");
        } else {
            $this->mode = $mode;
            $this->fileName = $filename;
        }
    }

    /**
     * Changes the mode of the application between
     * start and stop depending on the mode given
     * @return bool
     */
    public function toggleHalt()
    {
        if ($this->mode === 'halt') {
            $string = "<?php die('SITE IS DOWN FOR MAINTENANCE, TRY AGAIN LATER')\r\n;";
        } elseif ($this->mode == 'start') {
            $string = "<?php\r\n";
        } else {
            return false;
        }
        $arrFile = file($this->fileName);
        $arrFile[0] = $string;
        return $this->writeFile(implode($arrFile));
    }


    public function toggleEnvironment()
    {
        switch ($this->mode)
        {
            // Turn on the auto-detector
            case 'auto':
                $string = "define('APP_ENVIRONMENT', null);\r\n";
                break;

            // if it accidentally got here from a start/stop command
            case 'start':
            case 'halt':
                return false;
                break;

            // all other cases, set the environment
            default:
                $string = "define('APP_ENVIRONMENT', '".$this->mode."');\r\n";
                break;
        }
        $arrFile = file($this->fileName);
        $arrFile[24] = $string;
        return $this->writeFile(implode($arrFile));
    }


    /**
     * Writes changes out to the environment file
     * @param $content
     * @return bool
     */
    public function writeFile($content)
    {
        $handle = fopen($this->fileName, "w");
        $result = fwrite($handle, $content);
        if ($result !== strlen($content) || $result === 0 ){
            return false;
        } else {
            return true;
        }
    }
}
