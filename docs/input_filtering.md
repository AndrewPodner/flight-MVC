#Input Filtering

The `core\helper\Filter` class includes several static methods that can be useful for filtering data that comes into the application.  Many of the filters are just simple implementations of built in methods like `ctype`, but as filtering in web applications is an ever evolving process, using these static wrapper methods, you can adapt your filtering requirements as time goes on by simply updating the static method code rather than endlessly hunt for filtering logic throughout the application.

The following filters are built into the class:
* filename:  by default, accepts alphanumerics plus periods and underscores
* integer
* numeric
* alphanumeric
* alpha
* email (implements the Email type on filter_var)
* boolean
* pageName: by default accepts alphanumeric plus underscore
* databaseField: by default, accepts alphanumerics plus periods and underscores
* checkDateIsValid: this one needs simplification work, I know, but it is functional for now.  I am sure there are better ways

Usage:
`Filter::numeric($data)`

Returns `true` or `false` depending on if the data is valid.


