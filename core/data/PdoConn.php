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
     * Configuration for the specific database
     * @var
     */
    public $dbConfig;



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
                $table = $this->camelCaseToUnderscore($arr[0]);
                $field = $this->camelCaseToUnderscore($arr[1]);
                $where = $params[0];
                $retVal = $this->filter($table, $field, $where);
                break;

            // Handler for SELECT Statements
            case 'get':
                $arr = explode('By', substr($method, 3));
                $table = $this->camelCaseToUnderscore($arr[0]);
                $field = $this->camelCaseToUnderscore($arr[1]);
                $where = $params[0];
                $retVal = $this->get($table, $field, $where);
                break;

            // Handler for SELECT ALL RECORDS
            case 'all':
                $table = $this->camelCaseToUnderscore(substr($method, 3));
                $retVal = $this->getAll($table);
                break;

            // Handler for INSERT Statements
            case 'ins':
                $table = $this->camelCaseToUnderscore(substr($method, 6));
                $arrInsert = $params[0];
                $retVal = $this->insert($table, $arrInsert);
                break;

            // Handler for UPDATE Statements
            case 'upd':
                $arr = explode('By', substr($method, 6));
                $table = $this->camelCaseToUnderscore($arr[0]);
                $where = array($this->camelCaseToUnderscore($arr[1]) =>  $params[0]);
                $set = $params[1];
                $retVal = $this->update($table, $set, $where);
                break;

            // Handler for DELETE Statements
            case 'del':
                $arr = explode('By', substr($method, 6));
                $table = $this->camelCaseToUnderscore($arr[0]);
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
        $dbname = $this->setDbName($dbname);

        // Load the Connection Configuration
        $config = $this->config->item($dbname);

        // Put the Connection Info Into a class property
        $this->dbConfig = $config;

        // Build the appropriate connection string
        $connStr = $this->createConnectionString($config);

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
        $table = $this->dbConfig['db_prefix'] . $table;

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
        $table = $this->dbConfig['db_prefix'] . $table;

        $stmt = $this->conn->prepare("select * from $table where $field = ?");
        $stmt->bindParam(1, $where);

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
     * The where clause for this function uses an in statement
     *
     * @param $table
     * @param $field
     * @param $where (string containing the in parameters)
     * @return mixed
     * @throws \PDOException
     */
    public function getIn($table, $field, $where)
    {
        $table = $this->dbConfig['db_prefix'] . $table;

        $stmt = $this->conn->prepare("select * from $table where $field in ($where)");
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
     * This method accepts any valid sql statement
     *
     * @param $sql
     * @return mixed
     * @throws \PDOException
     */
    public function doSql($sql)
    {
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
     * @param string $table table name
     * @return array
     * @throws \PDOException
     */
    public function getAll($table)
    {
        $table = $this->dbConfig['db_prefix'] . $table;

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
        $table = $this->dbConfig['db_prefix'] . $table;

        $i=1;
        foreach ($set as $fieldName => $value) {
            $arrSet[$i] = "$fieldName = ?";
            $param[$i] = $value;
            $i++;
        }
        $strSet = implode(',', $arrSet);
        $whereValue = current($where);

        $stmt = $this->conn->prepare('UPDATE '. $table .' SET ' . $strSet . ' WHERE ' . key($where) . ' = ?');

        foreach ($param as $paramNo => &$value) {
            $stmt->bindParam($paramNo, $value);
        }
        $p = count($param);
        $stmt->bindParam($p+1, $whereValue);

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
        $table = $this->dbConfig['db_prefix'] . $table;

        // Build the Fields and Values part of the insert statement
        $i=1;
        foreach ($arrInsert as $fieldName => $value) {
            $fields[] = $fieldName;
            $values[$i] = $value;
            $param[$i] = '?';
            $i++;
        }
        $strFields = implode(',', $fields);
        $strValues = implode(',', $param);

        // Prepare the sql statement
        $stmt = $this->conn->prepare('INSERT INTO '.$table.' ('.$strFields.') VALUES ('.$strValues.')');
        foreach ($values as $paramNo => &$val) {
           $stmt->bindParam($paramNo, $val);
        }

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
        $table = $this->dbConfig['db_prefix'] . $table;
        $whereValue = current($where);

        $sql = "DELETE FROM $table WHERE " . key($where) . "= ? ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $whereValue);

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
     * Builds a connection string based on the database configuration
     * @param array $config
     * @return string
     */
    protected function createConnectionString(array $config)
    {
        // Build the appropriate connection string
        switch ($config['driver']) {

            // Connection String for MySQL
            case 'mysql':
                $connStr = $config['driver'] . ":host=" . $config['host'] . ";dbname=" . $config['db_name'];
                if ( $config['port'] !== '') {
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
                $connStr = $config['driver'] . ":" . $config['db_path'] . "/" . $config['db_filename'];
                break;

            case 'pgsql':
                $connStr = $config['driver'] . ":dbname=" . $config['db_name'] . ";host=" . $config['host'];
                if ($config['port'] !== '') {
                    $connStr .= ";port=" . $config['port'];
                }
                break;
        }
        return $connStr;
    }

    /**
     * Sets the database connection name or default if null
     *
     * @param null $dbname
     * @return string
     */
    protected function setDbName($dbname = null)
    {
        if (is_null($dbname)) {
            $strDbName = 'db_default';
        } else {
            $strDbName = 'db_' . $dbname;
        }
        return $strDbName;
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
