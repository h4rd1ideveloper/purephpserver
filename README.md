# Simple php http server 

[![](https://policarpoyan.firebaseapp.com/img/apple-touch-icon.png)](https://www.linkedin.com/in/yanpolicarpo)

Pure PHP Server is a microframework for management of request and response, server-side.

  - Pure PHP
  - All code according to PSR-FIG recommendations.
  - Retro-compatibility
  - no-Magic

# New Features!

  - Read env files with native php code-script.
  - Middleware.


You can also:
  - Work with Stream Object Abstractions.

### Tech

Pure PHP Server uses a number of open source projects to work properly:

* [guzzle/psr7] - Request and Response Abstractions
* [phpunit/phpunit] - library for unit testing
* [ext-pdo] - Database Abstraction

### Installation

Pure PHP Server requires [Composer](https://getcomposer.org/) to run.
Install the dependencies and devDependencies and start the server.

```sh
$ composer install
$ composer update
$ composer dump-autoload
```
Remember to create an .env file with the example below.
```sh
  path_root           = pathToRootProject/
  show_erros          = true
  cors                = true
  DB_type             = 
  DB_HOST             = 
  DB_USER             = 
  DB_PASS             =
  DB_NAME             = 
```
### Development

Want to contribute? Great!

Make a change in your file and instantanously see your updates!

### Todos

 - Write MORE Tests
 - Register Routes as Regex

License
----

MIT

