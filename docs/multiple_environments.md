#Mutliple Environments

Multiple environments are supported through the use of environment specific configuration files, which are stored in the `/config` directory.

The environment is determined in 2 ways:
* In the `/config/environment.php` file, you can explicitly set the environment by providing a value for the `APP_ENVIRONMENT` constant
* If you leave the `APP_ENVIRONMENT` constant as a null value, then the auto-detector will take over from and try to determine if you are in a development or testing environment.  The `$_SERVER` superglobal array elements `HTTP_HOST, SERVER_NAME, SERVER_ADDRESS, and REMOTE_ADDR` will be searched for a match to anything containing:
    * 127.0.0.1
    * localhost
    * any domain ending in *.dev
    * any domain ending in *.local

If a match is found, the environment will be set to `development`.  If no match is found, the environment will be set to testing.  The auto detect script will not put the application into the production environment under any circumstances, this must be done manually.

> The superglobal values and the values that trigger a positive response to go into development mode can be adjusted in the `/core/global_functions.php` file.

Other custom environments like `staging` can be deployed by adding a `staging.php` file in the `/config` directory, but it will be necessary to explicitly set the environment from the `environment.php` file.
