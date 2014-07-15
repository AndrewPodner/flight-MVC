<?php
/**
 * File Description: Configuration file for testing environment
 *
 * @category   configuration
 * @package    appplication
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2013
 * @license    /license.txt
 */

// DEFAULT SITE TITLE
$config['default_title'] = "Flight-MVC Default Title";

// DATABASE CREDENTIALS
$config['db_default']['driver'] = ''; //mysql, sqlsvr, odbc, sqlite, pgsql
$config['db_default']['db_user'] = '';
$config['db_default']['db_password'] = '';

//sqlsrv & ODBC connections
$config['db_default']['dsn'] = '';  //use for odbc & sqlsrv drivers

//MYSQL & PGSQL specific settings
$config['db_default']['host'] = '';  //use for mysql/pgsql driver
$config['db_default']['port'] = '';  //use for mysql/pgsql driver
$config['db_default']['db_name'] = ''; //use for mysql/pgsql driver


//SQLite specific settings
$config['db_default']['db_path'] = null; // use full path to file (if null, default dir is `/data/sqlite`
$config['db_default']['db_filename'] = '';

// DATABASE SETTINGS
$config['db_prefix'] = ""; //this is a prefix for table names in the database

// PATH FOR ERROR LOG
$config['error_log_path'] = 'error_log/';


