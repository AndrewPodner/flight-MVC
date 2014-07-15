#Using Views

Most of the documentation for views can be found by looking at the documentation for Flight at [http://flightphp.com].  The main thing to understand about how this extension implements views is that the views are simply organized into the `application/views` folder.  The bootstrapper sets the default view path to this directory.

I recommend adding a subfolder for each controller to keep things tidy.

You can call views in a subfolder as follows:

```
//let's say we want to render the application/views/home/index.php view file.

\Flight::render('home/index', array());

```

Layouts work the same way as views as far as directory and file structure are involved.  The Flight documentation explains how layouts/templates are implemented.
