<?php


namespace Kouakou\Aymard\Tests;


use Cake\TestSuite\IntegrationTestTrait;
use PHPUnit\Framework\TestCase;

class CorsMiddlewareTest extends TestCase
{
    use IntegrationTestTrait;

    const BASE_ORIGIN = 'http://test.com';
    protected $extension;
    private $server = [];

    public function setUp(): void
    {
        $this->server = [
            'REQUEST_URI' => '/test',
            'HTTP_ORIGIN' => 'http://test.com',
            'REQUEST_METHOD' => 'OPTIONS',
        ];

        parent::setUp();

        $this->useHttpServer(true);
    }

}