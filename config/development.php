<?php
/**
 * File Description: Configuration file for development environment
 *
 * @category   configuration
 * @package    application
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2014
 * @license    /license.txt
 */
// DEFAULT SITE TITLE
$config['default_title'] = "Flight-MVC Default Title";

// DATABASE DRIVER & CREDENTIALS
$config['db_default']['driver'] = 'sqlite'; // mysql, sqlsrv, sqlite, odbc, pgsql
$config['db_default']['db_user'] = '';
$config['db_default']['db_password'] = '';

//SQLSRV & ODBC connections
$config['db_default']['dsn'] = '';  //use for odbc & sqlsrv drivers

//MYSQL & PGSQL specific settings
$config['db_default']['host'] = '';  //use for mysql/pgsql driver
$config['db_default']['port'] = '';  //use for mysql/pgsql driver
$config['db_default']['db_name'] = ''; //use for mysql/pgsql driver

//SQLite specific settings (directory and file must be writeable)
$config['db_default']['db_path'] = null; // use full path to file (if null, default dir is `/data/sqlite`
$config['db_default']['db_filename'] = 'unit_test.db';

// DATABASE TABLE SETTINGS
$config['db_prefix'] = '';

// PATH FOR ERROR LOG
$config['error_log_path'] = 'error_log/';
