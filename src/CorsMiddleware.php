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
    private function apply(CorsBuilder $corsBuilder): CorsBuilder
    {
        $maxAge = sprintf(self::PATTERN, self::CORS_TAG, self::CORS_MAX_AGE_TAG);
        $allowOrigin = sprintf(self::PATTERN, self::CORS_TAG, self::CORS_ALLOW_ORIGIN_TAG);
        $allowMethods = sprintf(self::PATTERN, self::CORS_TAG, self::CORS_ALLOW_METHODS_TAG);
        $allowHeaders = sprintf(self::PATTERN, self::CORS_TAG, self::CORS_ALLOW_HEADERS_TAG);
        $exposeHeaders = sprintf(self::PATTERN, self::CORS_TAG, self::CORS_EXPOSE_HEADERS_TAG);

        return $corsBuilder
            ->allowOrigin(Configure::check($allowOrigin) ? Configure::read($allowOrigin) : ['*'])
            ->allowMethods(Configure::check($allowMethods) ? Configure::read($allowMethods) : ['GET', 'HEAD', 'OPTIONS', 'POST', 'PUT'])
            ->allowHeaders(Configure::check($allowHeaders) ? Configure::read($allowHeaders) : ['Authorization', 'Content-Type', 'Origin', 'Accept', 'X-Requested-With'])
            ->exposeHeaders(Configure::check($exposeHeaders) ? Configure::read($exposeHeaders) : ['Cache-Control', 'Content-Language', 'Content-Type', 'Expires', 'Last-Modified', 'Pragma'])
            ->maxAge(Configure::check($maxAge) ? (int)Configure::read($maxAge) : 600)
            ->allowCredentials();
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
            $response = $this->apply($response->withVary('Origin')->cors($request))->build();
        }

        return $next($request, $response);
    }

}
