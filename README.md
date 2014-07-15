#Flight MVC

## Description
This is my take on a simple extension to implement a more 'classic' MVC pattern that runs on top of the Flight micro-framework.  Flight is a great, lightweight framework for PHP.  I happen to find it a little easier to work in a more traditional MVC type of structure.  This extension of Flight establishes that structure and also adds in a few features like:

* a PDO wrapper
* a Router to read the controller, command, and 2 parameters
* multiple environments and config files
* a debugger function
* a logger
* an HTML head section builder
* a input filter class (static methods)

##Installation
Clone or download this repository and then run Composer's update feature to install dependencies (like Flight!).  You can modify the `composer.json` files section on `require-dev` to get rid of or add development libraries like Phing, Codesniffer, phpUnit, etc.

## Unit testing
All of the classes in this mod are unit tested with phpUnit, you will find the tests in the /tests directory

## Documentation
Documentation for Flight is at: [http://flightphp.com]

All extension documentation is in the Docs folder.  You can also view the documentation using the `doc` controller (e.g. `http://example.com/doc`)

## Contributing
Fork this repository and send a pull request.  Please stick to issues on the issue tracker or to-do's in the individual files as my intent is to keep this repo as lightweight as possible, and I do not intend to make it a full featured extension of the Flight framework.  If you have an idea for a feature, submit it to the issue tracker and let's talk it out before you start coding.  I have some ideas too, but some may end up as different extensions rather than features in this extension of Flight.  Remember, it is a 'micro-framework' after all.

###Conventions
This extension follows PSR-1, PSR-2, and PSR-4 conventions for autoloading, structure, and style.  All contributions should follow these conventions.  I recommend checking with Codesniffer to be sure.

##Thanks/Credits
* Mike Cao: developer of the Flight micro-framework [http://flightphp.com]
* Emanuil Rusev: for the Parsedown Library that runs the doc viewer [http://erusev.com]
