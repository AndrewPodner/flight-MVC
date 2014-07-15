<?php
/**
 * File Description:
 * Wrapper for PDO Connections with Dynamic CRUD Methods [using __call()]
 *
 * @category   data
 * @package    core
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2014
 * @license    /license.txt
 *
 */
namespace fmvc\core\data;

class PdoConn
{
    /**
     * PDO Connection Object
     * @var \PDO object
     */
    public $conn;

    /**
     * Configuration parameters (Dependency)
     * @var Config object
     */
    public $config;


    /**
     * Constructor, Initializes Dependencies and calls the initialization
     * method to establish the PDO data connection
     *
     * @param array $arrDep
     * @param string $dbname
     * @throws \Exception
     */
    public function __construct(array $arrDep = array(), $dbname = null)
    {
        if (empty($arrDep)) {
            throw new \Exception('Dependency Failure');
        } else {
            foreach ($arrDep as $key => $object) {
                $this->$key = $object;
            }
        }
        $this->init($dbname);
    }

    /**
     * Dynamic method handler for basic CRUD functions
     *
     * Format for dynamic methods names -
     * Create:  insertTableName($arrData)
     * Retrieve: getTableNameByFieldName($value)
     * Retrieve all: allTableName()
     * Update: updateTableNameByFieldName($value, $arrUpdate)
     * Delete: deleteTableNameByFieldName($value)
     * Filter (wildcards): filterTableNameByFieldName($value)
     *
     * @param $method
     * @param $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        $action = substr($method, 0, 3);
        $retVal = null;
        switch ($action)
        {
            // Handler for SELECT Statements that do wildcards with an asterisk
            case 'fil':
                $arr = explode('By', substr($method, 6));
                $table = $this->config->item('db_prefix') . $this->camelCaseToUnderscore($arr[0]);
                $field = $this->camelCaseToUnderscore($arr[1]);
                $where = $params[0];
                $retVal = $this->filter($table, $field, $where);
                break;

            // Handler for SELECT Statements
            case 'get':
                $arr = explode('By', substr($method, 3));
                $table = $this->config->item('db_prefix') . $this->camelCaseToUnderscore($arr[0]);
                $field = $this->camelCaseToUnderscore($arr[1]);
                $where = $params[0];
                $retVal = $this->get($table, $field, $where);
                break;

            // Handler for SELECT ALL RECORDS
            case 'all':
                $table = $this->config->item('db_prefix') . $this->camelCaseToUnderscore(substr($method, 3));
                $retVal = $this->getAll($table);
                break;

            // Handler for INSERT Statements
            case 'ins':
                $table = $this->config->item('db_prefix') . $this->camelCaseToUnderscore(substr($method, 6));
                $arrInsert = $params[0];
                $retVal = $this->insert($table, $arrInsert);
                break;

            // Handler for UPDATE Statements
            case 'upd':
                $arr = explode('By', substr($method, 6));
                $table = $this->config->item('db_prefix') . $this->camelCaseToUnderscore($arr[0]);
                $where = array($this->camelCaseToUnderscore($arr[1]) =>  $params[0]);
                $set = $params[1];
                $retVal = $this->update($table, $set, $where);
                break;

            // Handler for DELETE Statements
            case 'del':
                $arr = explode('By', substr($method, 6));
                $table = $this->config->item('db_prefix') . $this->camelCaseToUnderscore($arr[0]);
                $field = $this->camelCaseToUnderscore($arr[1]);
                $where = $params[0];
                $retVal = $this->delete($table, array($field => $where));
                break;
        }
        return $retVal;
    }

    /**
     * Initialize a connection to the datasource via PDO
     * Switches the connection string based upon the driver being used
     *
     * @param string $dbname  Name of the database (without `db_`) as specified in the config file
     * @return PdoConn
     * @throws \Exception
     *
     * @TODO: Add support for adding a custom db driver / dsn string that is compatible with PDO?
     */
    public function init($dbname = null)
    {
        // Set the connection name or use default
        if (is_null($dbname)) {
            $strDbName = 'db_default';
        } else {
            $strDbName = 'db_' . $dbname;
        }

        // Load the Connection Configuration
        $config = $this->config->item($strDbName);

        // Build the appropriate connection string
        switch ($config['driver']) {

            // Connection String for MySQL
            case 'mysql':
                $connStr = $config['driver'] . ":host=" . $config['host'] . ";dbname=" . $config['db_name'];
                if (! is_null($config['port']) && $config['port'] !== '') {
                    $connStr .= ";port=" . $config['port'];
                }
                break;

            // Connection String for SQL Server 2005 and higher
            case 'sqlsrv':
                $connStr = $config['driver'] . ":" . $config['dsn'];
                break;

            // Connection String for ODBC connections
            case 'odbc':
                $connStr = $config['driver'] . ":" . $config['dsn'] .";Uid=" . $config['db_user']
                    . ";Pwd=" . $config['db_password'];
                break;

            // Connection String for SQLite
            case 'sqlite':
                // Set the user & password to blank if there is no config variable set
                if (! isset ($config['db_user'])) {
                    $config['db_user'] = '';
                    $config['db_password'] = '';
                }

                //assume the default path if a configured path is not provided
                if (is_null($config['db_path'])) {
                    $path = './data/sqlite';
                } else {
                    $path = rtrim($config['db_path'], '/');
                }

                $connStr = $config['driver'] . ":" . $path . "/" . $config['db_filename'];
                break;

            case 'pgsql':
                $connStr = $config['driver'] . ":dbname=" . $config['db_name'] . ";host=" . $config['host'];
                if (! is_null($config['port']) && $config['port'] !== '') {
                    $connStr .= ";port=" . $config['port'];
                }
                break;
        }
        // Establish a PDO connection or terminate application
        try {
            $this->conn = new \PDO($connStr, $config['db_user'], $config['db_password']);
            return $this;
        } catch (\PDOException $e) {
            throw new \Exception("data connection error: " . $e->getMessage());
        }
    }

    /**
     * Executes a prepared statement via PDO to return a recordset
     * data returned will be as: $array[0]['field_name']
     * This script will replace an asterisk with a % sign to allow
     * for wildcard searches.
     *
     * @param $table
     * @param $field
     * @param $where
     * @param null $sort
     * @throws \PDOException
     * @return mixed
     */
    public function filter($table, $field, $where, $sort = null)
    {
        if (! stristr($where, '*')) {
            $where .= '%';
        } else {
            $where = str_replace('*', '%', $where);
        }
        $sql = "select * from $table where $field like '$where'";
        if (! is_null($sort)) {
            $sql .= " ORDER BY $sort";
        }

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            $err = $this->conn->errorInfo();
            throw new \PDOException('Query Failure ['.$err[2].']');
        } else {
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
    }

    /**
     * Executes a prepared statement via PDO to return a recordset
     * data returned will be as: $array[0]['field_name']
     *
     * @param $table
     * @param $field
     * @param $where
     * @return mixed
     * @throws \PDOException
     */
    public function get($table, $field, $where)
    {
        $stmt = $this->conn->prepare("select * from $table where $field = '$where'");
        if ($stmt === false) {
            $err = $this->conn->errorInfo();
            throw new \PDOException('Query Failure ['.$err[2].']');
        } else {
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
    }

    /**
     * Executes a prepared statement via PDO to return a recordset
     * data returned will be as: $array[0]['field_name']
     *
     * @param string $table table name
     * @return array
     * @throws \PDOException
     */
    public function getAll($table)
    {
        $stmt = $this->conn->prepare("select * from $table");
        if ($stmt === false) {
            $err = $this->conn->errorInfo();
            throw new \PDOException('Query Failure ['.$err[2].']');
        } else {
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
    }
    /**
     * Executes a prepared statement via PDO to update the
     * database
     *
     * @param string $table  name of database table
     * @param array $set set parameters (field => value)
     * @param array $where where parameter (field => value)
     * @return int affected rows
     * @throws \PDOException
     */
    public function update($table, array $set, array $where)
    {
        foreach ($set as $fieldName => $value) {
            $arrSet[] = "$fieldName = '$value'";
        }
        $strSet = implode(',', $arrSet);
        $sql = "UPDATE $table SET $strSet WHERE " . key($where) . " = '" .current($where). "'";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            $err = $this->conn->errorInfo();
            throw new \PDOException('Query Failure ['.$err[2].']');
        } else {
            $stmt->execute();
            return $stmt->rowCount();
        }
    }

    /**
     * Executes a prepared statement for PDO to insert a record into the database
     * @param string $table
     * @param array $arrInsert
     * @return int affected rows
     * @throws \PDOException
     */
    public function insert($table, array $arrInsert)
    {
        // Build the Fields and Values part of the insert statement
        foreach ($arrInsert as $fieldName => $value) {
            $fields[] = $fieldName;
            $values[] = "'".$value."'";
        }
        $strFields = implode(',', $fields);
        $strValues = implode(',', $values);

        // Prepare the sql statement
        $sql = "INSERT INTO $table ($strFields) VALUES ($strValues)";
        $stmt = $this->conn->prepare($sql);

        // Execute or throw exception on failure
        if ($stmt === false) {
            $err = $this->conn->errorInfo();
            throw new \PDOException('Query Failure ['.$err[2].']');
        } else {
            $stmt->execute();
            return $this->lastInsertId();
        }
    }


    /**
     * Execute a prepared statement to delete a record(s) via
     * PDO Connection
     *
     * @param string $table name of table
     * @param array $where  (field_name => value)
     * @return int affected rows
     * @throws \PDOException
     */
    public function delete($table, array $where)
    {
        $sql = "DELETE FROM $table WHERE " . key($where) . "='" .current($where). "'";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            $err = $this->conn->errorInfo();
            throw new \PDOException('Query Failure ['.$err[2].']');
        } else {
            $stmt->execute();
            return $stmt->rowCount();
        }
    }


    /**
     * Changes a camelCase table or field name to lowercase,
     * underscore spaced name
     *
     * @param  string $string camelCase string
     * @return string underscore_space string
     */
    public function camelCaseToUnderscore($string)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $string));
    }

    /**
     * Returns the ID of the last inserted row or sequence value
     *
     * @param  string $param Name of the sequence object from which the ID should be returned.
     * @return string representing the row ID of the last row that was inserted into the database.
     */
    public function lastInsertId($param = null)
    {
        return $this->conn->lastInsertId($param);
    }
}
