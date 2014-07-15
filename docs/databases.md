#Working with Databases

Native support for database connections is managed through a wrapper class for the PDO class.  Presently, connections to the following databases are supported in the wrapper.  All methods in the wrapper class use prepared statements.
* Mysql
* SQL Server
* ODBC
* Sqlite
* Postgres

##Connecting
Connecting to a database is managed via the configuration file.  The database configuration resides in a multi-dimensional array where each top level element is the configuration for a data connection (e.g. `$confg['db_default'])

`db_default` is the fallback connection that will be used if no connection is specified when the `PdoConn` class is instantiated.  If a configuration is specified, only the part after the `db_` is passed into the instantiation statement.  For instance, if there is a configuration called `$config['db_foo']`, it is called with the following statement:

```

$foo = new \core\data\PdoConn(array('config' => \Flight::config()), 'foo');

```
The PDO connection can be directly accessed from the PdoConn object using the `conn` property (e.g. `$foo->conn`)

The array elements for each type of database vary from platform to platform.  The `config/config_template.php` file gives more details as to which elements are needed for each database system.
* Mysql
    * driver: 'mysql'
    * host
    * db_user
    * db_password
    * db_name
* SQL Server
    * driver: 'sqlsrv'
    * dsn
    * db_user
    * db_password
* ODBC
    * driver: 'odbc'
    * dsn
    * db_user
    * db_password
* Sqlite
    * driver: 'sqlite'
    * db_path  (if null, `/data/sqlite` is the default path)
    * db_filename
* Postgres
    * driver: 'pgsql'
    * host
    * db_user
    * db_password
    * db_name

##Dynamic Methods

The PdoConn class supports a variety of dynamic method names using the PHP `__call()` magic method.  There are 6 database operations supported via dynamic methods.  Method names are always in camelCase with the first letter being lowercase (e.g. `getTableByField`).  The system converts camelCase to underscores in the database, so your database fields and tables should always be in underscore.

####Example
The dynamic method `getCompanyByCompanyName('Example')` would look for an exact match to 'Example' in the `company_name` field of the `company` table.

>NOTE: you can specify a table prefix in the database configuration, `$config['db_prefix']` such as `ABC_`.  If you add a prefix, then the dynamic call to `getCompanyByCompanyName('Example')` would look for the `ABC_company` table.

* get:  This is the basic select method.  It accepts 1 parameter, which is the value of the field you are searching.  The get method looks for exact matches to whatever parameter is specified.  Usage: `$foo->getCompanyByAccountStatus('active')` looks for companies with an account status of active.  Results are given in a multidimensional array as follows: `$row[0]['field_name']`

* filter: This is a select method that accepts and sorts.  It accepts 1 parameter, which is the value of the field you are searching.  The get method looks for exact matches to whatever parameter is specified.  Usage: `$foo->filterCompanyByAccountStatus('act*')` looks for companies with an account status that begins with `act`.  Asteriks are used as wildcards.  Results are given in a multidimensional array as follows: `$row[0]['field_name']`

* all:  This will retrieve all fields of all rows for the specified table.  Usage: `$foo->allCompany()` is the same as saying "SELECT * FROM company";

* insert:  This mimics the SQL insert method.  The parameter is a multidimensional array in the form of `$row[0]['field_name'] = 'value'` Each record to be inserted must have the same number of fields.  Any number of fields in the table can be part of the insert statement.  It returns the last insert id (return value may not work properly in ODBC).

* update: This mimics the SQL update method.  The parameters are 2 arrays, the first array is a the field names and values in the form `$row['field_name']='value'` and the second is an array for the where clause in the form of `$where['field_name']='value'`.  Returns the number of affected rows.

* delete:  Works similar to get.  Usage: `$foo->deleteCompanyByStatus('inactive')` will delete all companies with the status 'inactive'.  Returns the number of affected rows.

##Other methods
The get, filter, update, insert, delete, and getAll methods can also be used without calling a dynamic method name.  These methods are all documented in the PDOConn class method comments.

For more complex queries, it is recommended that the PDO connection be called directly using the `conn` propery (e.g. $foo->conn->prepare()).  Dynamic methods are provided to reduce the lines of code needed to execute simple CRUD operations.