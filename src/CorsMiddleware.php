<?php


namespace Kouakou\Aymard;


use Cake\Core\Configure;
use Cake\Http\CorsBuilder;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CorsMiddleware
{
    private const PATTERN = '%s.%s';

    public const CORS_TAG = "Cors";
    public const CORS_ALLOW_ORIGIN_TAG = "AllowOrigin";
    public const CORS_ALLOW_METHODS_TAG = "AllowMethods";
    public const CORS_ALLOW_HEADERS_TAG = "AllowHeaders";
    public const CORS_ALLOW_CREDENTIALS_TAG = "AllowCredentials";
    public const CORS_EXPOSE_HEADERS_TAG = "ExposeHeaders";
    public const CORS_MAX_AGE_TAG = "MaxAge";

    /**
     * @param CorsBuilder $corsBuilder
     * @return CorsBuilder
     */
    private function getDefault(CorsBuilder $corsBuilder): CorsBuilder
    {
        return $corsBuilder
            ->allowOrigin('*')
            ->allowMethods(['GET', 'OPTIONS', 'PUT', 'PATCH', 'POST', 'DELETE'])
            ->allowHeaders(['Authorization', 'Content-Type', 'Origin'])
            ->allowCredentials();
    }

    /**
     * @param CorsBuilder $corsBuilder
     * @return CorsBuilder
     */
    private function apply(CorsBuilder $corsBuilder): CorsBuilder
    {
        $allowOrigin = sprintf(self::PATTERN, self::CORS_TAG, self::CORS_ALLOW_ORIGIN_TAG);
        if (Configure::check($allowOrigin)) {
            $corsBuilder = $corsBuilder->allowOrigin(Configure::read($allowOrigin));
        }

        $allowMethods = sprintf(self::PATTERN, self::CORS_TAG, self::CORS_ALLOW_METHODS_TAG);
        if (Configure::check($allowMethods)) {
            $corsBuilder = $corsBuilder->allowMethods((array)Configure::read($allowMethods));
        }

        $allowHeaders = sprintf(self::PATTERN, self::CORS_TAG, self::CORS_ALLOW_HEADERS_TAG);
        if (Configure::check($allowHeaders)) {
            $corsBuilder = $corsBuilder->allowHeaders((array)Configure::read($allowHeaders));
        }

        $exposeHeaders = sprintf(self::PATTERN, self::CORS_TAG, self::CORS_EXPOSE_HEADERS_TAG);
        if (Configure::check($exposeHeaders)) {
            $corsBuilder = $corsBuilder->exposeHeaders((array)Configure::read($exposeHeaders));
        }

        $maxAge = sprintf(self::PATTERN, self::CORS_TAG, self::CORS_MAX_AGE_TAG);
        if (Configure::check($maxAge)) {
            $corsBuilder = $corsBuilder->maxAge((int)Configure::read($maxAge));
        }

        $allowCredentials = sprintf(self::PATTERN, self::CORS_TAG, self::CORS_ALLOW_CREDENTIALS_TAG);
        if (Configure::check($allowCredentials)) {
            $corsBuilder = $corsBuilder->allowCredentials();
        }

        return $corsBuilder;
    }

    /**
     * @param ServerRequestInterface $request The request.
     * @param ResponseInterface $response The response.
     * @param callable $next Callback to invoke the next middleware.
     * @return ResponseInterface A response
     */
    public function __invoke($request, $response, $next)
    {
        if (Configure::read('debug') && ($response instanceof Response) && ($request instanceof ServerRequest)) {
            $corsBuilder = $response->cors($request);
            $corsBuilder = !Configure::check(self::CORS_TAG) ? $this->getDefault($corsBuilder) : $this->apply($corsBuilder);
            $response = $corsBuilder->build();
        }

        return $next($request, $response);
    }

}
