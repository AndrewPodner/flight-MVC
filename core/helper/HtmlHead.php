<?php
/**
 * File Description:
 * Class for building the <head> section
 * of an HTML file.  This allows specifying of CSS and JS files
 * from a controller command.
 *
 * This class is automatically initialized in the bootstrap and is
 * preloaded & available in the controller at all times.
 *
 * Requires the config object as a dependency
 *
 * @category   helper
 * @package    core
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2013
 * @license    /license.txt
 *
 * @todo: add support for other HTML head elements
 * @todo: add an auto-detector to allow a string or an array to be passed to style() and script() methods
 */
namespace fmvc\core\helper;

class HtmlHead
{
    /**
     * Configuration Dependency
     * @var Config
     */
    public $config;

    /**
     * Array of script tags
     * @var array
     */
    public $c_scripts = array();

    /**
     * Array of style tags
     * @var array
     */
    public $c_styles = array();

    /**
     * Page Title
     * @var
     */
    public $c_title;

    public function __construct(array $arrDep = array())
    {
        // Load Dependencies
        if (empty($arrDep)) {
            throw new \Exception('Dependency Failure');
        } else {
            foreach ($arrDep as $key => $object) {
                $this->$key = $object;
            }
        }

        // Set the default page title
        $this->c_title =  $this->config->item('default_title');
    }

    /**
     * Adds a Javascript File to the Array for the HTML HEAD
     * @param array $arrFile
     * @return Controller
     * @throws \Exception
     */
    public function script(array $arrFile = array())
    {
        if (empty($arrFile)) {
            throw new \Exception('No JavaScript Files Provided');
        }
        foreach ($arrFile as $file) {
            $script = '<script type="text/javascript" src="/web/scripts/'.$file.'.js"></script>';
            $this->c_scripts[] = $script;
        }
        return $this;
    }

    /**
     * Adds a CSS File to the Array for the HTML HEAD
     * @param array $arrFile
     * @return Controller
     * @throws \Exception
     */
    public function style(array $arrFile = array())
    {
        if (empty($arrFile)) {
            throw new \Exception('No CSS Files Provided');
        }
        foreach ($arrFile as $file) {
            $style = '<link rel="stylesheet" href="/web/css/'.$file.'.css" />';
            $this->c_styles[] = $style;
        }
        return $this;
    }

    /**
     * Changes the Page Title from the default
     * @param string $title
     * @return Controller
     * @throws \Exception
     */
    public function title($title = null)
    {
        if (is_null($title)) {
            throw new \Exception('No Title Provided');
        }
        $this->c_title = $title;
        return $this;
    }

    /**
     * Returns the HTML HEAD Contents
     * @return string
     */
    public function head()
    {
        $head = '';
        if (! empty($this->c_scripts)) {
            foreach ($this->c_scripts as $link) {
                $head .= $link . '
                ';
            }
        }
        if (! empty($this->c_styles)) {
            foreach ($this->c_styles as $link) {
                $head .= $link . '
                ';
            }
        }
        $head .= '<title>'.$this->c_title.'</title>
        ';
        return $head;
    }
}
