<?php
/**
 * File Description:
 *
 * Class for managing the display of Flight-MVC documentation
 *
 * @category   core
 * @package    helper
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2013
 * @license    /license.txt
 */
namespace fmvc\core\helper;

class Documentation
{

    /**
     * Name of the Documentation Directory
     * @var null|string
     */
    public $dirName;


    public function __construct($dirName = null)
    {
        if (is_null($dirName)) {
            $this->dirName = './docs';
        } else {
            $this->dirName = $dirName;
        }
    }

    /**
     * Put the list of files in the directory into an array
     * @return array
     */
    public function getDocumentationFileNames()
    {
        $output = array();
        $dirList = scandir($this->dirName);
        foreach ($dirList as $file) {
            if (stristr($file, '.md')) {
                $output[] = $file;
            }
        }
        return $output;
    }

    /**
     * uses the \Parsedown class to convert markdown to HTML
     *
     * @param $filename
     * @param \Parsedown $parser
     * @return string
     */
    public function parseDocumentationFile($filename, \Parsedown $parser)
    {
        $markdown = file_get_contents($this->dirName . '/' . $filename);
        return $parser->text($markdown);
    }
}
