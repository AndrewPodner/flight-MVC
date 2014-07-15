#Flight-MVC Command Line Interface
Flight-MVC has a command line interface to make some tasks more automated in an effort to speed up development and maintenance.  The following functions are available from the command line:
* Start or Halt the site
* Set the environment (development, testing, etc.)
* Create a Model file
* Create a Controller file
* Create a View file

##Usage:
Call the command line interface (from the application root directory )using: `php -f cli/fmvc.php commandName parameter1 parameter2`

##Start/Stop and Environment
The `environment` command can be used to start or halt the site as well as change the environment of the application.

####Valid Parameters
* halt:  inserts code into the `config\environment.php` file that causes the all code to stop executing
* start: restarts the site by removing the halt code
* auto: puts the environment into auto-detect mode
* <environment>: you can pass development, testing, or any other valid environment mode you have specified.

####Examples
```
//shut down the site
php -f cli/fmvc.php environment halt

//start the site from a halt
php -f cli/fmvc.php environment start

//change to development environment
php -f cli/fmvc.php environment development
```

##Create a model
