#Using the Logger

The logger is meant to be very simple and intended to implement basic logging functions. The logger class is not loaded by default into the Controller class, and needs to be instantiated.

##Configuring
The config object must be passed into the logger.

The configuration file for each environment contains a `$config['error_log_path']` element which is set to the `error_log/`; directory by default.

By default, the logger has 4 logging levels:
* event
* notice
* warning
* error

These levels are setup in the `core/helper/Logger.php` class file in the $foo->levels property of the class.  You can modify these logging levels as desired by changing the contents of the array.

## Using the logger:
Dynamic methods are used to log events.  If you want to log a warning, for example, then you would use the `$foo->warning()` method.  The dynamic logger methods accept 2 parameters.  The first parameter is the message or description of the log event and the second parameter is any data you want to capture with the log event.

The logger stores logged events in a text file in the directory that is specified.  The name of the file will correspond to the log level like `warning.txt`, `notice.txt`, and so forth.




