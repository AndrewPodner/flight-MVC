#Using Models

Models are where most of the work should be getting done in your application.  This is where the processing takes place and then the results are passed back to the controller and then rendered to a view.

All models in this extension can pretty well stand on their own and bring in dependencies as needed.  The only requirement is adding an appropriate namespace based on the location of the file which at the simplest level will be `application\model`.  You can add more subfolders for organization as needed, just be sure to include the appropriate namespace

You are free to handle your models however you see fit, but the best result will be found in using solid coding practices and observing the concepts of dependency injection, writing testable code, etc.  Volumes have been written on these subjects, and you will hopefully find in a study of this extension's code, that every effort was made to develop using these best practices.