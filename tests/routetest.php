<?php

namespace redcathedral\tests;

use PHPUnit\Framework\TestCase;
use redcathedral\phpMySQLAdminrest\Facades\JWTFacade;
use function redcathedral\phpMySQLAdminrest\App;


/**
 * @brief RouteTests tests the phpleage router with http-like-requests.
 */
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
     * @uses \redcathedral\phpMySQLAdminrest\App
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\AuthMiddleware
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin
     * @covers \redcathedral\phpMySQLAdminrest\Controller\DatabaseController::listDatabasesAsJson
     * @covers \redcathedral\phpMySQLAdminrest\Controller\DatabaseController::__construct
     * @covers \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\RouterConfigurationProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers \redcathedral\phpMySQLAdminrest\Facades\JWTFacade
     
     */

    public function testIsAllowedToRouteToListDatabasesAsJson(): void
    {

        $token = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000,
            "uuid" => "64646464"
        );

        $jwt = JWTFacade::encode($token);

        $_SERVER['REQUEST_URI'] = '/api/database';

        $_SERVER['HTTP_AUTHORIZATION'] = $jwt;

        $request = \Laminas\Diactoros\ServerRequestFactory::fromGlobals();

        $response = $this->router->dispatch($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @brief testIsNotAllowedToRouteToListDatabasesAsJson
     * @description The test is supposed to fail with a 403.
     * @uses \redcathedral\phpMySQLAdminrest\App
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\AuthMiddleware
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin
     * @covers \redcathedral\phpMySQLAdminrest\Controller\DatabaseController::listDatabasesAsJson
     * @covers \redcathedral\phpMySQLAdminrest\Controller\DatabaseController::__construct
     * @covers \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\RouterConfigurationProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers \redcathedral\phpMySQLAdminrest\Facades\JWTFacade
     */


    public function testIsNotAllowedToRouteToListDatabasesAsJson(): void
    {
        $_SERVER['REQUEST_URI'] = '/api/database';

        $request = \Laminas\Diactoros\ServerRequestFactory::fromGlobals();

        $response = $this->router->dispatch($request);

        $this->assertEquals(403, $response->getStatusCode());
    }

    /**
     * tearDown()
     */
    public function tearDown(): void
    {
        $_SERVER = null;
    }
}
