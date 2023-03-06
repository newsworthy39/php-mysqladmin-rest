<?php

namespace redcathedral\tests;

use PHPUnit\Framework\TestCase;
use redcathedral\phpMySQLAdminrest\Facades\JWTFacade;
use function redcathedral\phpMySQLAdminrest\App;
use function redcathedral\phpMySQLAdminrest\Dispatch;

/**
 * @brief RouteTests tests the phpleage router with http-like-requests.
 */
final class RouteTest extends TestCase
{

    /**
     * @brief testIsNotAllowedToRouteToListDatabasesAsJson
     * @description The test is supposed to SUCCEED with 200 OK.
     * @uses \redcathedral\phpMySQLAdminrest\App
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\JWTAuthMiddleware
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
        // Make a token 
        $jwt = JWTFacade::encode(array(
            "aud" => "me",
            "uuid" => "64646464"
        ));

        $_SERVER['REQUEST_URI'] = '/api/database';
        $_SERVER['HTTP_AUTHORIZATION'] = sprintf("Bearer %s", $jwt);
        $request = \Laminas\Diactoros\ServerRequestFactory::fromGlobals();

        $response = Dispatch($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsString(sprintf("%s", $response->getBody()));
    }

    /**
     * @brief testIsNotAllowedToRouteToListDatabasesAsJson
     * @description The test is supposed to fail with a 403.
     * @uses \redcathedral\phpMySQLAdminrest\App
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\JWTAuthMiddleware
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

        $response = Dispatch($request);

        $this->assertEquals(403, $response->getStatusCode());
    }

    /**
     * @brief testIsNotAllowedToRouteToListDatabasesAsJson
     * @description The test is supposed to fail with a 403.
     * @uses \redcathedral\phpMySQLAdminrest\App
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\JWTAuthMiddleware
     * @covers \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\RouterConfigurationProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers \redcathedral\phpMySQLAdminrest\Facades\JWTFacade
     * @covers \redcathedral\phpMySQLAdminrest\Controller\JWTController::getJWTToken
     */
    public function testIsAllowedToObtainJWT(): void
    {
        $_SERVER['REQUEST_URI'] = '/api/authenticate';

        $request = \Laminas\Diactoros\ServerRequestFactory::fromGlobals();

        $response = Dispatch($request);

        $this->assertEquals(200, $response->getStatusCode());
    }



    /**
     * tearDown()
     */
    public function tearDown(): void
    {
        $_SERVER = null;
    }
}
