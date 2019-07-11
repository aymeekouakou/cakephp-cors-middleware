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
    private const PATTERN = "%s.%s";
    public const CORS_TAG = "Cors";

    /**
     * @param CorsBuilder $corsBuilder
     * @return CorsBuilder
     */
    private function apply(CorsBuilder $corsBuilder): CorsBuilder
    {
        if ($this->checkCors()) {
            if ($this->check(Cors::ALLOW_ORIGIN)) {
                $corsBuilder = $corsBuilder->allowOrigin($this->read(Cors::ALLOW_ORIGIN));
            }

            if ($this->check(Cors::ALLOW_METHODS)) {
                $corsBuilder = $corsBuilder->allowMethods((array)$this->read(Cors::ALLOW_METHODS));
            }

            if ($this->check(Cors::ALLOW_HEADERS)) {
                $corsBuilder = $corsBuilder->allowHeaders((array)$this->read(Cors::ALLOW_HEADERS));
            }

            if ($this->check(Cors::EXPOSE_HEADERS)) {
                $corsBuilder = $corsBuilder->exposeHeaders((array)$this->read(Cors::EXPOSE_HEADERS));
            }

            if ($this->check(Cors::MAX_AGE)) {
                $corsBuilder = $corsBuilder->maxAge($this->read(Cors::MAX_AGE));
            }

            if ($this->read(Cors::ALLOW_CREDENTIALS, false)) {
                $corsBuilder = $corsBuilder->allowCredentials();
            }
        } else {
            $corsBuilder = $corsBuilder
                ->allowOrigin(CorsDefault::DEFAULT_ALLOW_ORIGIN)
                ->allowMethods(CorsDefault::DEFAULT_ALLOW_METHODS)
                ->allowHeaders(CorsDefault::DEFAULT_ALLOW_METHODS);
        }

        return $corsBuilder;
    }

    private function pattern($value): string
    {
        return sprintf(self::PATTERN, self::CORS_TAG, $value);
    }

    private function checkCors(): bool
    {
        return Configure::check(self::CORS_TAG);
    }

    private function check($value): bool
    {
        return Configure::check($this->pattern($value));
    }

    private function read($value, $default = null)
    {
        return $this->check($value) ? Configure::read($this->pattern($value)) : $default;
    }

    /**
     * @param ServerRequestInterface $request The request.
     * @param ResponseInterface $response The response.
     * @param callable $next Callback to invoke the next middleware.
     * @return ResponseInterface A response
     */
    public function __invoke($request, $response, $next)
    {
        if ($response instanceof Response && $request instanceof ServerRequest) {
            $response = $this->apply($response->withVary('Origin')->cors($request))->build();
        }

        return $next($request, $response);
    }

}
