<?php
/**
 * File Description:  Config File for setting the application
 * current running mode / environment
 *
 * The following values are expected:
 * `development`
 * `testing`
 * `production`
 *
 * Custom modes (e.g. `staging` can be used, but putting the app into
 * a custom mode or into production mode must be done explicitly
 * from this file.
 *
 *
 * @category   configuration
 * @package    application
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2013
 * @license    /license.txt
 */

// Define the running mode if desired
// If set to null, the auto-detector will take over
define('APP_ENVIRONMENT', null);