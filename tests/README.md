## Unit Testing for Flight-MVC

All of the code for the MVC extension is tested using phpUnit.  For the PdoConn class, there is a `unit_test.db` sqlite database in the `/data/sqlite` subfolder.  This database is used for some read/write tests.  If you find that the tests are failing, make sure that the directory that the database is in is writeable.
