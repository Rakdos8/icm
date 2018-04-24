# ICM
ICE Is Coming To EVE website to handle API key through ESI system.

## Installation
+ Update the web/conf/config.ini file
+ Put all of those file in your web server (with PHP, see req.)
+ Run the composer command: `composer install`

Some of the feature are not implemented yet, so be patient, will come soon(TM).

## Requirements
+ PHP version: 7.0.0 minimum
+ MySQL version: 5.7 maximum
+ PhpBB version: 3.3 minimum

## Enhancements (ToDo list)
+ Bootstrap design (coz' SmartPhone is life)
+ Install a Redis server to save some data in it instead of file caching (less I/O is better).
+ Build a top layer of Eseye to handle properly requests and Exceptions to the view.
+ Adds a way to multi insert in batch instead of wild looping `INSERT INTO...`.
+ Adds a SQL abstraction for the `WHERE` part
