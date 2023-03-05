<?php

namespace redcathedral\tests;

use PHPUnit\Framework\TestCase;
use function redcathedral\phpMySQLAdminrest\App;


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
    public function testCanRouteToListDatabasesAsJson(): void
    {
        $_SERVER['REQUEST_URI'] = '/api/database';

        $request = \Laminas\Diactoros\ServerRequestFactory::fromGlobals( );
        
        $response = $this->router->dispatch($request);
                
        $this->assertIsArray(json_decode(sprintf("%s", $response->getBody())));

    }

    /**
     * tearDown()
     */
    public function tearDown(): void
    {
    }
}
