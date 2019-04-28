[![Build Status](https://travis-ci.com/aymeekouakou/cakephp-cors-middleware.svg?branch=master)](https://travis-ci.com/aymeekouakou/cakephp-cors-middleware)

A CakePHP (3.7+) middleware for activate cors domain in your application. [Middleware docs](https://book.cakephp.org/3.0/en/controllers/middleware.html).

[Learn more about CORS](https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS)

## Requirements

- PHP version 7.2 or higher
- CakePhp 3.7 or higher

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```bash
composer require aymardkouakou/cakephp-cors-middleware
```

Ensure that debug mode is activated:
```php
// In config/app.php

...

'debug' => filter_var(env('DEBUG', true), FILTER_VALIDATE_BOOLEAN),

...
```

## Quick Start

Adding the Middleware:

```php
// In src/Application.php

$middlewareQueue

    ...
    
    ->add(CorsMiddleware::class)
    // OR 
    ->add(new CorsMiddleware())
    
    ...
    
```

By default the middleware authorize cors for all origins, all methods and all headers. No configuration required for work fine.

## Custom configuration

For using custom configuration, you must adding a ````Cors```` array in your ````config/app.php```` file.

````php
'Cors' => [

    // Authorized options here
    
    'AllowOrigin' => string|array,
    'AllowMethods' => array,
    'AllowHeaders' => array,
    'ExposeHeaders' => array,
    'AllowCredentials' => true|false,
    'Maxge' => int
    
]
````

## Authorized options

#### Allow all domains
 
````php
'AllowOrigin' => '*'
// Or
'AllowOrigin' => ['*']
````

#### Allow one domain only
 
````php
'AllowOrigin' => 'localhost:4200'
````

#### Allow multiple domains
 
````php
'AllowOrigin' => [
    'localhost:4200',
    ...
]
````
