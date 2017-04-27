Developer Details
=========
Chris Hodgson
hello@chrishodgsonweb.co.uk

Introduction
==========
This is the SkyBet Technical Test and has been built in PHP 7.1. 

I started to built this as a bespoke MVC app but then changed over to using Symfony to take 
advantage of many of the features that I normally take for granted such as router, view functionality.   
 
The data store used is a JSON file which you can find inside the data folder.

Unit Tests
==========
To run the unit tests, you cd into the respository folder and do `bin/phpunit`.

Performance Considerations
==========
The Solution uses a Json file as the data source as instructed, but this will not scale and a better 
solution would be to use a database. The reason for this is because whenever we try to read or update 
the Json data we have to read the entire file rather than just the rows that are required.

Notes
=====
The `PersonHydratorInterface` and `PersonModelInterface` have been added to allow for flexibility 
incase we decide to switch data source to something other than Json in the future. 