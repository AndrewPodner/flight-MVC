<?php
/**
 * File Description: Configuration file sample/template
 * You can copy this and name the file to your desired configuration
 * This file is not intended to be used in an application
 *
 *
 * @category   configuration (SAMPLE FILE / TEMPLATE)
 * @package    application
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2014
 * @license    /license.txt
 */
//Turn Sessions On or Off
define('SESSIONS_ENABLED', true);

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
$config['db_default']['db_path'] = './data/sqlite'; // required field
$config['db_default']['db_filename'] = '';



// DATABASE SETTINGS
$config['db_prefix'] = ""; //this is a prefix for table names in the database

// PATH FOR ERROR LOG
$config['error_log_path'] = 'error_log/';

// USE WINDOWS AUTHENTICATION
$config['use_windows_auth'] = false;
$config['auth_domain'] = 'MYDOMAIN\\'; //use this to strip the domain name from a windows user name
