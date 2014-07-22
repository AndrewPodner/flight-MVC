<?php
/**
 * File Description:
 *
 * @category
 * @package
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2014
 * @license    /license.txt
 */
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    print_r(getallheaders());
    echo file_get_contents("php://input");
} else {
    echo 'ERROR: not a PUT Request';
}
