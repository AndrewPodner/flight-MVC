<?php
/**
 * File Description:
 *
 * Configuration class.
 *
 * No dependencies are needed, just an array with
 * the currently loaded configuration.
 *
 * @category   lib
 * @package    core
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2014
 * @license    /license.txt
 */
namespace fmvc\core\lib;

class Config
{
    /**
     * Configuration settings
     * @var array
     */
    public $c_config = array();

    /**
     * Constructor: load configuration into property
     *
     * @param mixed $config
     * @return Config
     * @throws \Exception
     */
    public function __construct(array $config = null)
    {
        if (is_null($config)) {
            throw new \Exception('No Configuration Variables Found');
        }
        $this->c_config = $config;
        return $this;
    }

    /**
     * Retrieve a configuration item. If no item
     * name is provided, return all configuration items
     *
     * @param null|string $arg
     * @return mixed
     */
    public function item($arg = null)
    {
        if (is_null($arg)) {
            return $this->c_config;
        } else {
            return $this->c_config[$arg];
        }
    }

    /**
     * Set a new item for the configuration, used for adding runtime
     * configuration properties
     *
     * @param string $varName
     * @param string $value
     * @return Config
     * @throws \Exception
     */
    public function set($varName = null, $value = null)
    {
        if (is_null($varName) || is_null($value)) {
            throw new \Exception('Invalid data passed to configuration set');
        }
        $this->c_config[$varName] = $value;
        return $this;
    }
}
