<?php
/**
 * File Description: Base Class for Controllers
 *
 * Required Dependencies:  Config, Input
 *
 * In the router, config, input, and html head are always
 * loaded into the controller class.
 *
 * @category   lib
 * @package    core
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2014
 * @license    /license.txt
 */
namespace fmvc\core\lib;

class Controller
{
    /**
     * Configuration Dependency
     * @var Config
     */
    public $config;

    /**
     * Input Dependency
     * @var Input
     */
    public $input;

    /**
     * Name of the current class
     * @var string
     */
    public $class;

    /**
     * Base url of the web server
     * @var
     */
    public $base_server;

    /**
     * A message to pass to the UI
     * @var string
     */
    public $message;

    /**
     * Array of the URL Parameters
     * @var array
     */
    public $param = array();

    public function __construct(array $arrDep = array(), array $arrParam = array())
    {
        // Load Dependencies
        if (empty($arrDep)) {
            throw new \Exception('Dependency Failure');
        } else {
            foreach ($arrDep as $key => $object) {
                $this->$key = $object;
            }
        }

        // Load Parameters into Class Property
        if (! empty($arrParam)) {
            foreach ($arrParam as $value) {
                $this->param[] = $value;
            }
        }

        if (isset($this->input->server['HTTP_HOST'])) {
            $this->base_server = $this->input->server['HTTP_HOST'];
        } else {
            $this->base_server = $this->input->server['DOCUMENT_ROOT'];
        }

        $this->class = $this->setClassName();

    }

    /**
     * Get the name of the current Class
     * @return string Class name
     */
    protected function setClassName()
    {
        $class = get_class($this);
        $arr = explode('\\', $class);
        return end($arr);
    }
}
