CompetitieBeheer-php-api
========================

A PHP implementation of the API of CompetitieBeheer.nl.

You'll need an account at CompetitieBeheer.nl to be able to do something usefull
with this implementation. Please see CompetitieBeheer.nl for more information.

This code is provided as-is, without any explicit support.

Caching
-------
The code does various requests to CompetitieBeheer.nl. The responses can be
cached to lighten the load on the servers and your internet connection. You'll
need to decide what kind of caching you want:
 - FileCache: responses are stored in a given directory.
 - MySQLCache: responses are stored in a given database table.
 - NoCache: responses are not stored - no caching is done.
 - RequestCache: responses are stored in memory per HTTP request. When the PHP
   process is done, the responses are lost. In effect, each API call will be
   executed at most once per PHP run.

Example usage
-------------
```text
<?php
# Include the main file
require_once(dirname(__file__) . '/CompetitieBeheer-php-api/CompetitieBeheer.php');

# Include the type of cache you want
require_once(dirname(__file__) . '/CompetitieBeheer-php-api/cache/RequestCache.php');

# Create the cache of your choice
$cache = new RequestCache();

# The club id and club hash are specific for you account. Please see the
# documentation on CompetitieBeheer.nl on how to obtain these values.
$clubId = 123;
$clubHash = "ABCABCABC";

# Create the API object
$api = new CompetitieBeheer($cache, $clubId, $clubHash);

# Now you can use the API, for example to get a list of teams
$teams = $api->getTeams();
?>
```