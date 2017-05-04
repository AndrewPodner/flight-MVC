<?php
/**
 * File Description:
 * Class for Managing Superglobals
 *
 * Puts the contents of superglobals into class
 * properties
 *
 * Dependencies required: none
 *
 * @category   lib
 * @package    core
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2014
 * @license    /license.txt
 */
namespace fmvc\core\lib;

class Input
{
    /**
     * POST superglobal array
     * @var array
     */
    public $post = array();

    /**
     * GET superglobal Array
     * @var array
     */
    public $get = array();

    /**
     * SESSION superglobal array
     * @var array
     */
    public $session = array();

    /**
     * SERVER superglobal array
     * @var array
     */
    public $server = array();

    /**
     * COOKIE superglobal array
     * @var array
     */
    public $cookie = array();

    /**
     * Constructor: initializes the superglobals into
     * class properties, loads test data if we are in
     * development mode
     *
     * @return Input
     */
    public function __construct()
    {
        // Initialize the class properties
        $this->loadSuperglobals();

        return $this;
    }

    /**
     * Put superglobal arrays into class properties
     * @return Input
     */
    public function loadSuperglobals()
    {
        if (is_array($_POST)) {
            $this->post= array_merge($this->post, $_POST);
        }

        if (is_array($_SERVER)) {
            $this->server = array_merge($this->server, $_SERVER);
        }

        if (is_array($_GET)) {
            $this->get = array_merge($this->get, $_GET);
        }

        if (is_array($_COOKIE)) {
            $this->cookie = array_merge($this->cookie, $_COOKIE);
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            $this->session = array_merge($this->session, $_SESSION);
        } else {
            unset($this->session);
        }

        return $this;
    }

    /**
     * Wrapper for setting a session variable
     * @param string $key array key for the session variable
     * @param string $value value of the session variable
     * @return Input
     */
    public function session($key, $value)
    {
        $_SESSION[$key] = $value;
        return $this;
    }

    /**
     * Wrapper for setting a cookie
     *
     * @param string $key  name of the cookie
     * @param string $value value for the cookie
     * @param integer $lengthSec life of the cookie (30day default)
     * @return mixed cooke
     */
    public function cookie($key, $value, $lengthSec = 2592000)
    {
        return setcookie($key, $value, time()+$lengthSec);
    }
}
