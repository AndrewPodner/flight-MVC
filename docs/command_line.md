#Flight-MVC Command Line Interface
Flight-MVC has a command line interface to make some tasks more automated in an effort to speed up development and maintenance.  The following functions are available from the command line:
* Start or Halt the site
* Set the environment (development, testing, etc.)
* Create a Model file
* Create a Controller file
* Create a View file

##Usage:
Call the command line interface (shown here from the `cli` directory) using: `./fmvc commandName parameter1`

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
./fmvc environment halt

//start the site from a halt
./fmvc environment start

//change to development environment
./fmvc environment development
```

##Create a model, controller, or view
The command line interface helps to automate the creation of common MVC file to speed up development.  From the command line, you can create a new controller, model, or view file that will contain all of the code needed to get each type of object started.

When creating an object, you can also specify subdirectory (and child namespace for models & controllers) to create the object in.  If the subdirectory does not exist, it will be created for you.  Additionally, the object generator will also check to see if the file you are trying to create already exists.

####Examples:
Create a controller called `Test` in the `application/controller` directory
```
./fmvc controller test
```

Create a controller called `Test` in the `application/controller/sample` directory
```
./fmvc controller sample/test
```

Create a model called `Sample` in the `application/model` directory
```
./fmvc model sample
```

Create a view called main.php in the `view/home` directory
```
./fmvc view home/main
```

