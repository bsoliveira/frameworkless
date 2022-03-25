# Frameworkless

An example of a modern PHP application bootstrapped without a framework.

## System requirements

 - PHP 7.4 or newer

## Install

```
$ git clone https://github.com/bsoliveira/frameworkless.git

$ cd frameworkless

$ chmod -R 755 storage/ 

$ composer install
```

Create the database with the collation `utf8_unicode_ci`, and import the schema: resources/database/schema.sql

Edit the file `.env` and set the values according to your development environment.

```
DB_HOST       = "127.0.0.1"
DB_PORT       = "3306"
DB_DATABASE   = "database"
DB_USERNAME   = "dbusername"
DB_PASSWORD   = "dbpassword"
```

Start the php server.

```
$ php -S localhost:8080 -t public public/index.php;

```

## List of Dependencies:

- [php-di/php-di](https://github.com/PHP-DI/PHP-DI): PHP-DI is a dependency injection container meant to be practical, powerful, and framework-agnostic.
- [monolog/monolog](https://github.com/Seldaek/monolog): Monolog - Logging for PHP.objects.in RESTful APIs, and works really well with JSON.
- [nikic/fast-route](https://github.com/nikic/FastRoute): FastRoute - Fast request router for PHP;
- [laminas/laminas-diactoros](https://github.com/laminas/laminas-diactoros): laminas-diactoros is a PHP package containing implementations of the PSR-7 HTTP message interfaces and PSR-17 HTTP message factory interfaces.
- [relay/relay](https://github.com/relayphp/Relay.Relay): A PSR-15 request handler.
- [narrowspark/http-emitter](https://github.com/narrowspark/http-emitter): Emits a Response to the PHP Server API.
