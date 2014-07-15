#Debugging

There is a global function called `debug()` that can be called.  It will provide an easily readable view of whatever array / object you are trying to view

Additionally, there are a few commands in the default `Debug` controller like:
* `this`:  shows the debug dump of the Controller object
* `info`: shows `phpinfo()
* `config`: show the debug dump of the config object

These commands do not work in production mode, but it is highly recommended to delete them from the controller or delete the controller entirely if they are not needed to protect application security.

