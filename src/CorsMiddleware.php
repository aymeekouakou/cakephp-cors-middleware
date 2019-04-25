<?php


namespace App\Middleware;


use Cake\Http\CorsBuilder;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use http\Exception\BadHeaderException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CorsMiddleware
{
    private $_cors = null;
    private $_allow_tag = "allow";
    private $_allow_age_tag = "age";
    private $_allow_origin_tag = "origin";
    private $_allow_method_tag = "method";
    private $_allow_header_tag = "header";
    private $_allow_expose_tag = "expose";
    private $_allow_credential_tag = "credential";

    private $_opts = [];
    private $_max_age = 0;
    private $_origins = [];
    private $_methods = [];
    private $_allow_headers = [];
    private $_expose_headers = [];

    public function __construct(
        array $options = [
            $this->_allow_tag => [
                $this->_allow_origin_tag,
                $this->_allow_method_tag,
                $this->_allow_header_tag,
                $this->_allow_expose_tag,
                $this->_allow_credential_tag,
                $this->_allow_age_tag
            ]
        ]
    )
    {
        if (empty($options) || !isset($this->_allow_tag, $options)) {
            throw new BadHeaderException("You must provide options params first.");
        }

        $this->_opts = $options;
    }

    private function _apply($method, $values = null)
    {
        if (($this->_cors instanceof CorsBuilder) && (method_exists($this->_cors, $method))) {
            $this->_cors = ($values !== null) ? $this->_cors->{$method}($values) : $this->_cors->{$method}();
        }
    }

    private function _applyAllowOrigin()
    {
        if (array_key_exists($this->_allow_origin_tag, $this->_opts[$this->_allow_tag])) {
            $this->_apply("allowOrigin", $this->_origins);
        }
    }

    private function _applyAllowMethods()
    {
        if (array_key_exists($this->_allow_method_tag, $this->_opts[$this->_allow_tag])) {
            $this->_apply("allowMethods", $this->_methods);
        }
    }

    private function _applyAllowHeaders()
    {
        if (array_key_exists($this->_allow_header_tag, $this->_opts[$this->_allow_tag])) {
            $this->_apply("allowHeaders", $this->_allow_headers);
        }
    }

    private function _applyAllowCredentials()
    {
        if (array_key_exists($this->_allow_credential_tag, $this->_opts[$this->_allow_tag])) {
            $this->_apply("allowCredentials");
        }
    }

    private function _applyExposeHerders()
    {
        if (array_key_exists($this->_allow_expose_tag, $this->_opts[$this->_allow_tag])) {
            $this->_apply("exposeHeaders", $this->_expose_headers);
        }
    }

    private function _applyMaxAge()
    {
        if (array_key_exists($this->_allow_age_tag, $this->_opts[$this->_allow_tag])) {
            $this->_apply("maxAge", $this->_max_age);
        }
    }

    /**
     * @param ServerRequestInterface $request The request.
     * @param ResponseInterface $response The response.
     * @param callable $next Callback to invoke the next middleware.
     * @return ResponseInterface A response
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        if ($response instanceof Response && $request instanceof ServerRequest) {
            $this->_cors = $response->cors($request);

            if (!$this->_cors instanceof CorsBuilder) {
                throw new BadRequestException("");
            }

            $this->_applyAllowOrigin();
            $this->_applyAllowMethods();
            $this->_applyAllowHeaders();
            $this->_applyAllowCredentials();
            $this->_applyExposeHerders();
            $this->_applyMaxAge();

            $response = $this->_cors->build();
        }

        return $next($request, $response);
    }

    public function withOrigin(string $name)
    {
        $this->_origins[] = $name;

        return $this;
    }

    public function withOrigins(array $names)
    {
        if (!empty($names)) {
            foreach ($names as $name) {
                $this->withOrigin($name);
            }
        }

        return $this;
    }

    public function withMethod(string $name)
    {
        $this->_methods[] = strtoupper($name);

        return $this;
    }

    public function withMethods(array $names)
    {
        if (!empty($names)) {
            foreach ($names as $name) {
                $this->withMethod($name);
            }
        }

        return $this;
    }

    public function withAllowHeader(string $name)
    {
        $this->_allow_headers[] = strtolower($name);

        return $this;
    }

    public function withAllowHeaders(array $names)
    {
        if (!empty($names)) {
            foreach ($names as $name) {
                $this->withAllowHeader($name);
            }
        }

        return $this;
    }

    public function withExposHeader(string $name)
    {
        $this->_expose_headers[] = strtolower($name);

        return $this;
    }

    public function withMaxAge(int $number)
    {
        $this->_max_age[] = $number;

        return $this;
    }
}
