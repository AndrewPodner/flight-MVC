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
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    echo 'Working';
} else {
    echo 'ERROR: not a DELETE Request';
}