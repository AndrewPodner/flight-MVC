#Managing the `<head>` section of an HTML page

The `<head>` section of your HTML pages can be managed with the HTML Head helper class.  This class loads automatically when the controller loads.  This helper is designed primarily to manage 3 things:
* Including stylesheets (CSS files)
* Including Javascript files
* Manipulating the `<title>` tag of a page.

Other tags can be written in to the head section of your pages using views and/or layouts, but as stylesheets, scripts, and titles are often dynamic within the application, support for easy manipulation of these is provided via the helper.

The default title for the application can be set in the configuration file for each running environment using the `$config['default_title']` element of the configuration array.  This will be used if the title is not set at runtime.

##Usage
The class expects javascript file to be located in the `./web/scripts` directory, or a subdirectory of it.  It also expects javascript files to end in the `.js` file extension.  Likewise, stylesheets should be located in the `./web/css` directory (or a subdirectory) and end with the `.css` file extension.

To use the helper, call the `style()` or `script()` methods and pass an array of filenames (no extension) to the method.  If you were trying to load a file in subdirectory, your would prefix the filename with the directory name and a `/` character (e.g. `subdir/file`).  You can pass multiple array elements to load multiple script or stylesheet files with a single method call.

To change the page title, call the `title()` method and use a string for whatever title you want to use.

####Let's do an example....from a controller command:
```
public function index()
{
    //add a style sheet
    $this->head->style(array('main'));

    //add script files
    $this->head->script(array('foojava', 'barjava', 'foobar/java'));

    //change the title
    $this->head->title('My title');

    //Pass the head section as the $head variable to the view via a render call
    \Flight::render('home/index', array('html_head' => $this->head->head()));
}

```

####In the view....
```
<html>
    <head>
        <?=$html_head?>
    </head>
</html>

```

####Will produce.....
```
<html>
    <head>
        <script type="text/javascript" src="./web/scripts/foojava.js"></script>
        <script type="text/javascript" src="./web/scripts/barjava.js"></script>
        <script type="text/javascript" src="./web/scripts/foobar/java.js"></script>
        <link rel="stylesheet" href="./web/css/main.css" />
        <title>My title</title>
    </head>
</html>
```