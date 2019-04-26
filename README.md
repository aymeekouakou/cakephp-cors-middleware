[![Build Status](https://travis-ci.com/aymeekouakou/cakephp-cors-middleware.svg?branch=master)](https://travis-ci.com/aymeekouakou/cakephp-cors-middleware)

A CakePHP (3.7+) middleware for activate cors domain in your application with [Middleware](https://book.cakephp.org/3.0/en/controllers/middleware.html).

[Learn more about CORS](https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS)

## Requirements

- PHP version 7.2 or higher
- CakePhp 3.7 or higher

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require aymardkouakou/cakephp-cors-middleware
```

## Quick Start

Adding the Middleware

```PHP
// In src/Application.php
$middlewareQueue
    ...
    ->add(CorsMiddleware::class)
    // OR 
    ->add(new CorsMiddleware())
    ...
```

By default the middleware authorize cors for all origins, all methods and all headers for life. No configuration required.
