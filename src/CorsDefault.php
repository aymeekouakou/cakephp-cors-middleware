<?php


namespace Kouakou\Aymard;


abstract class CorsDefault
{
    public const DEFAULT_ALLOW_ORIGIN = '*localhost:4200';
    public const DEFAULT_ALLOW_METHODS = ['GET', 'HEAD', 'OPTIONS', 'POST', 'PUT'];
    public const DEFAULT_ALLOW_HEADERS = ['Authorization', 'Content-Type', 'Origin', 'Accept', 'X-Requested-With'];
    public const DEFAULT_ALLOW_CREDENTIALS = false;
    public const DEFAULT_EXPOSE_HEADERS = ['Cache-Control', 'Content-Language', 'Content-Type', 'Expires', 'Last-Modified', 'Pragma'];
    public const DEFAULT_MAX_AGE = 300;
}