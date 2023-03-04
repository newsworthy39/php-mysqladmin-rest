<?php

namespace redcathedral\tests;

use PHPUnit\Framework\TestCase;
use function redcathedral\phpMySQLAdminrest\App;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class RouteTest extends TestCase
{
    private $router;

    /**
     * setUp()
     */
    public function setUp(): void
    {
        try {
            $this->router = App()->get(\League\Route\Router::class);
            $this->assertIsObject($this->router);
        } catch (\League\Container\Exception\NotFoundException $ex) {
            $this->assertFalse(true);
        }
    }

    /**
     * @covers 
     */
    public function testCanRouteToJson(): void
    {
        $_SERVER['REQUEST_URI'] = '/api/test';

        $request = \Laminas\Diactoros\ServerRequestFactory::fromGlobals( );
        
        $response = $this->router->dispatch($request);
                
        $this->assertIsObject(json_decode(sprintf("%s", $response->getBody())));

    }

    /**
     * tearDown()
     */
    public function tearDown(): void
    {
    }
}
