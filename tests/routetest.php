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

        $this->assertEquals(401, $response->getStatusCode());
    }

    /**
     * @brief testIsNotAllowedToRouteToListDatabasesAsJson
     * @description The test is supposed to fail with a 403.
     * @uses \redcathedral\phpMySQLAdminrest\App
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\JWTAuthMiddleware
     * @uses \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @uses \redcathedral\phpMySQLAdminrest\Providers\RouterConfigurationProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers \redcathedral\phpMySQLAdminrest\Facades\JWTFacade
     * @covers redcathedral\phpMySQLAdminrest\Controller\AuthenticationController
     */
    public function testIsAllowedToObtainJWT(): void
    {
        $_SERVER['REQUEST_URI'] = '/api/authenticate';
        $token = base64_encode(sprintf("%s:%s", "admin", "admin"));
        $_SERVER['HTTP_AUTHORIZATION'] = sprintf("Basic %s",  $token);

        $request = \Laminas\Diactoros\ServerRequestFactory::fromGlobals();

        $this->assertEquals(200, Dispatch($request)->getStatusCode());
    }


    /**
     * @brief testIsNotAllowedToRouteToListDatabasesAsJson
     * @description The test is supposed to fail with a 403.
     * @uses \redcathedral\phpMySQLAdminrest\App
     * @uses \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @uses \redcathedral\phpMySQLAdminrest\Providers\RouterConfigurationProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers \redcathedral\phpMySQLAdminrest\Facades\JWTFacade
     * @covers redcathedral\phpMySQLAdminrest\Controller\AuthenticationController
     */
    public function testIsNotAllowedToObtainJWT(): void
    {
        $_SERVER['REQUEST_URI'] = '/api/authenticate';

        $request = \Laminas\Diactoros\ServerRequestFactory::fromGlobals();

        $this->assertEquals(401, Dispatch($request)->getStatusCode());
    }



    /**
     * tearDown()
     */
    public function tearDown(): void
    {
        $_SERVER = null;
    }
}
