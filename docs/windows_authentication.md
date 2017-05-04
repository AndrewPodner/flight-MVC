#Windows Authentication
Flight-MVC has optional Windows authentication built into the router.  To enable, 
set the configuration variable for using Windows authentication to `true`.

Note that you will also need to enable Windows Authentication and disable anonymous
authentication in IIS.

Once you have enabled Windows authentication, you can also enter a domain name (with
a trailing backslash) in the domain config variable to strip the domain name from
the user name if desired.

To add in more processing when the Windows authentication check takes place, modify
the `core\routes.php` file in the designate area to make it work for you.