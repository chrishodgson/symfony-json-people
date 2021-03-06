Introduction
====
This is a small web application built using PHP 7.1 & Symfony 3.2 manage contacts in a JSON data file. 

In addition to the standard Symfony install, I have added the `knplabs/knp-paginator-bundle` package to provide 
pagination of the people list when it exceeds 10 people.

The data store is a JSON file which you can find inside the data folder. 


System Requirements
========

- PHP version >= 7.1
- Composer # See https://getcomposer.org/ for more information and documentation.
- Optional - Ant and XDebug enabled in PHP to run the Code Analysis Reports below.
 
 
Installation instructions
===

- read the `System Requirements` 
- clone the repository: `git clone https://github.com/chrishodgson/symfony-json-contacts.git` 
- cd into the repository folder and run composer: `composer install`                      
- serve the application: `php bin/console server:start`
- visit `http://127.0.0.1:8000` in a web browser 
- stop the server afterwards: `php bin/console server:stop`


Unit and Functional Tests
===
These can be found in the `src/AppBundle/Tests` folder. They are all unit tests apart from the Controller folder 
which contains functional tests.


Full Code Analysis Report (requires Ant & Xdebug)
===

If you have `xdebug` enabled in PHP and `ant` installed then you can run the full code analysis report which checks 
syntax, software metrics, coding standards, duplicate code, unit tests and code coverage. 

- cd into the repository folder and run ant: `ant`

The tools output the following info:

- lint - performs a syntax check of the code by PHP
- phploc-ci - measures project size using PHPLOC 
- pdepend - calculates software metrics using PHP_Depend 
- phpcs-ci - finds any coding standard violations using PHP CodeSniffer
- phpcpd-ci - finds any duplicate code using PHPCPD
- phpunit - runs unit tests with PHPUnit and generates a code coverage report 

In addition to the output to the screen, a report will be generate a build folder containing a detailed information about 
the analysis including code coverage. If any problems are found, the tools will output `BUILD FAILED` otherwise 
it shows `BUILD SUCCESSFUL`. 

An html page shows the code coverage `build/coverage/index.html`. The code coverage should be 100%. 


Partial Code Analysis Report (requires Xdebug)
===

If you don't have `ant` installed but you do have `xdebug` enabled in PHP you can run phpunit directly to output a 
code coverage report to the screen. The code coverage should be 100%:

- cd into the repository folder and run phpunit: `bin/phpunit --coverage-text`


Browser Support
========

This app uses jQuery and Bootstrap which have certain restrictions when it comes to browser support, in particular Internet Explorer. More info...   
 
- https://jquery.com/browser-support/
- http://getbootstrap.com/getting-started/#support


Scalability & Performance 
=====

As requested in the brief for the test, the solution that has been built uses a Json file as the data source but this 
will not scale very well and a better solution would be to use a database. The reason for this is because whenever we try 
to read or update the Json data we have to read the entire file rather than just the required as you would do with a database.


Security 
=====
Symfony provides good security built into the framework. Cross-site scripting attacks are prevented 
by the blade templating language which santises all user supplied content before rendering as HTML. Server side 
validation is also provided using the Form validator together with the entity property constraints. 

Cross-site request forgery attacks are prevented by using a CSRF token inside the forms which are generated 
by the Form builder. 

Notes
=====
The interfaces `PersonHydratorInterface` and `PersonModelInterface` have been used to allow easier switching of 
dependencies (ie we might want to switch the data source from the Json file to database). 

Here are a list of the PHP classes (not including unit & functional tests):

- AppBundle/Controller/PersonController
- AppBundle/Entity/Person
- AppBundle/Form/PersonType
- AppBundle/Hydrator/PersonHydrator
- AppBundle/Hydrator/PersonHydratorInterface
- AppBundle/Model/PersonModel
- AppBundle/Model/PersonModelInterface
- AppBundle/Utils/JsonFileHandler