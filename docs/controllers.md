#Using Controllers

##General
* All controller classes must extend the `\core\lib\Controller` class
    * example: `class Home extends \core\lib\Controller`
* Controllers should typically be located in the `application\controller\` directory and should also be in that namespace as well
* The name of the controller class must be the same as its filename.

##Constructor
The constructor of the controller class must call the parent constructor, as shown below
```
    public function __construct($deps, $params)
    {
        parent::__construct($deps, $params);

    }
```

Other code can be added to the constructor (e.g. instantiating a data connection object into a controller class property), but the a call to the parent constructor must be part of the code.

##Dependencies
When a controller is called via the URL router, it is automatically loaded with the following dependencies:
* `$this->config`: The current application configuration
* `$this->input` : The object containing superglobals
* `this->head`: The object for managing the HTML head section of a view

>Note: You can modify how/what the router automatically loads by changing the `/core/routes.php` file around line 52.

##Parameters
The router also loads URL parameters into the controller each time it loads.  The parameters can be found in the `$this->param` property which is an array.  The first parameter is `$param[0]` and the second parameter will be loaded as `$param[1].

####Example
```
http://example.com/home/index/foo/bar

Controller: Home
Command: index()
$param[0] = 'foo'
$param[1] = 'bar'

```
>NOTE: at this time, only 2 parameters are supported.

##Commands
Each function (and they need to have public scope) serves a a command in the controller you are working in.  For example the URL: `http://example.com/home/index` will call the `index()` function in the `Home` class.  Every class should typically have an `index()` command, unless you are certain that this will not be needed.  If a URL is specified in a browser which has a controller but no command (e.g. `http://example.com/home`), the app will default to the `index()` command.  If there is not one, a 404 error will be displayed.

##Working inside a controller command
Within the command, you should be calling data from your models and then rendering it out to a view, or passing it however you want if your application is an API.

####Example
```
    public function viewdata()
    {
        //Open a connection to the data source
        $db = new \core\data\PdoConn(array('config' => \Flight::config()));

        //Get an array of all of the data in the companies table
        $rs = $db->getAll('companies');

        //Use Flight's rendering method to open the `views/home/viewdata.php`
        // view and put the data into the $data variable in the view.
        \Flight::render('home/viewdata', array('data' => $rs));
    }
```


