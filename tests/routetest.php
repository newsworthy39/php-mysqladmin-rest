<?php

namespace redcathedral\tests;

use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\StreamFactory;
use Laminas\Diactoros\UriFactory;
use PHPUnit\Framework\TestCase;
use function redcathedral\phpMySQLAdminrest\App;
use function redcathedral\phpMySQLAdminrest\Dispatch;
use redcathedral\phpMySQLAdminrest\Facades\JWTFacade;
use redcathedral\phpMySQLAdminrest\Implementations\HashSHA256;
use Laminas\Diactoros\Uri;

/**
 * @brief RouteTests tests the phpleage router with http-like-requests.
 */
final class RouteTest extends TestCase
{
    public function setUp(): void
    {
        $_SERVER = array(); // Fix for ServerRequestFactory
    }

    /**
     * @brief testIsNotAllowedToRouteToListDatabasesAsJson
     * @description The test is supposed to SUCCEED with 200 OK.
     * @uses \redcathedral\phpMySQLAdminrest\App
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\JWTAuthMiddleware
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin
     * @covers \redcathedral\phpMySQLAdminrest\Controller\DatabaseController
     * @covers \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\RouteConfigurationProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers \redcathedral\phpMySQLAdminrest\Facades\JWTFacade
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\CloudTrailMiddleware
     * @covers \redcathedral\phpMySQLAdminrest\Implementations\HashSHA256::__construct
     * @covers \redcathedral\phpMySQLAdminrest\Implementations\HashSHA256::fromString
     * @covers \redcathedral\phpMySQLAdminrest\Strategy\InMemoryAuthenticationStrategy::addUser
     */
    public function testIsAllowedToRouteToListDatabasesAsJson(): void
    {
        // Make a token 
        $jwt = JWTFacade::encode(array(
            "aud" => "me",
            "uuid" => "64646464"
        ));

        $request = \Laminas\Diactoros\ServerRequestFactory::fromGlobals()->withUri(new Uri('/api/database'))
            ->withAddedHeader('Content-Type', 'application/json')->withAddedHeader('Authorization', sprintf("Bearer %s", $jwt));

        $response = Dispatch($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsString(sprintf("%s", $response->getBody()));
    }

    /**
     * @brief testIsNotAllowedToRouteToListDatabasesAsJson
     * @description The test is supposed to fail with a 401.
     * @uses \redcathedral\phpMySQLAdminrest\App
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\JWTAuthMiddleware
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin
     * @covers \redcathedral\phpMySQLAdminrest\Controller\DatabaseController
     * @covers \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\RouteConfigurationProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers \redcathedral\phpMySQLAdminrest\Facades\JWTFacade
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\CloudTrailMiddleware
     * @covers \redcathedral\phpMySQLAdminrest\Implementations\HashSHA256::__construct
     * @covers \redcathedral\phpMySQLAdminrest\Implementations\HashSHA256::fromString
     * @covers \redcathedral\phpMySQLAdminrest\Strategy\InMemoryAuthenticationStrategy::addUser
     */
    public function testIsNotAllowedToRouteToListDatabasesAsJson(): void
    {
        $request = \Laminas\Diactoros\ServerRequestFactory::fromGlobals()->withUri(new Uri('/api/database'));
        $response = Dispatch($request);

        $this->assertEquals('/api/database', (string)$request->getUri());
        $this->assertEquals(401, $response->getStatusCode());
    }

    /**
     * @brief testIsAllowedToObtainJWT
     * @description The test is supposed to succeed with a 200.
     * @uses \redcathedral\phpMySQLAdminrest\App
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\JWTAuthMiddleware
     * @covers \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\RouteConfigurationProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers \redcathedral\phpMySQLAdminrest\Facades\JWTFacade
     * @covers redcathedral\phpMySQLAdminrest\Controller\AuthenticationController
     * @covers \redcathedral\phpMySQLAdminrest\Strategy\InMemoryAuthenticationStrategy
     * @covers \redcathedral\phpMySQLAdminrest\Implementations\HashSHA256
     * @covers \redcathedral\phpMySQLAdminrest\Strategy\AuthenticationStrategy
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\CloudTrailMiddleware
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\JWTAuthMiddleware
     */
    public function testIsAllowedToObtainJWT(): void
    {

        // Fetch the implementation
        $username = 'admin';
        $auth = App()->get(\redcathedral\phpMySQLAdminrest\Strategy\InMemoryAuthenticationStrategy::class);
        $auth->addUser($username, HashSHA256::fromString($username)); // Adds admin:admin

        // Execute against the route (uses fluent)   
        $token = sprintf("Basic %s", base64_encode(sprintf("%s:%s", $username, $username)));
        $request = ServerRequestFactory::fromGlobals()->withUri(new Uri('/api/authenticate'))
            ->withAddedHeader("Authorization", $token);

        $this->assertEquals($token, $request->getHeader("Authorization")[0]);
        $this->assertEquals(200, Dispatch($request)->getStatusCode());
    }

    /**
     * @brief testIsNotAllowedToObtainJWT
     * @description The test is supposed to fail with a 401.
     * @covers \redcathedral\phpMySQLAdminrest\App
     * @covers \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\RouteConfigurationProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers \redcathedral\phpMySQLAdminrest\Facades\JWTFacade
     * @covers redcathedral\phpMySQLAdminrest\Controller\AuthenticationController
     * @covers \redcathedral\phpMySQLAdminrest\Strategy\InMemoryAuthenticationStrategy
     * @covers \redcathedral\phpMySQLAdminrest\Implementations\HashSHA256
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\CloudTrailMiddleware
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\JWTAuthMiddleware
     */
    public function testIsNotAllowedToObtainJWT(): void
    {

        $request = ServerRequestFactory::fromGlobals()->withUri(new Uri('/api/authenticate'));

        $this->assertEquals('GET', (string)$request->getMethod());
        $this->assertEquals(401, Dispatch($request)->getStatusCode());
    }

    /**
     * @brief testIsNotAllowedToObtainJWT
     * @description The test is supposed to fail with a 401.
     * @covers \redcathedral\phpMySQLAdminrest\App
     * @covers \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\RouteConfigurationProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers \redcathedral\phpMySQLAdminrest\Facades\JWTFacade
     * @covers redcathedral\phpMySQLAdminrest\Controller\AuthenticationController
     * @covers \redcathedral\phpMySQLAdminrest\Strategy\InMemoryAuthenticationStrategy
     * @covers \redcathedral\phpMySQLAdminrest\Implementations\HashSHA256
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\CloudTrailMiddleware
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\JWTAuthMiddleware
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\JSONOnlyMiddleware
     * 
     * @covers \redcathedral\phpMySQLAdminrest\Controller\DatabaseController::__construct
     * @covers \redcathedral\phpMySQLAdminrest\Controller\DatabaseController::listDatabases
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin
     * @covers \redcathedral\phpMySQLAdminrest\Controller\DatabaseController::createDatabase
     * @covers \redcathedral\phpMySQLAdminrest\Exception\ServerErrorException
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin::hasDatabase
     */
    public function testIsAllowedToCreateDatabase(): void
    {
        $databasename = "michael";
        $mysqladmin = App()->get(\redcathedral\phpMySQLAdminrest\MySQLAdmin::class);
        if ($mysqladmin->hasDatabase($databasename)) {
            $mysqladmin->deleteDatabase($databasename);
        }

        $jwt = JWTFacade::encode(array(
            "aud" => "me",
            "uuid" => "64646464"
        ));

        $body = (new StreamFactory())->createStream(json_encode(array('name' => $databasename)));
        $request = ServerRequestFactory::fromGlobals()->withMethod('POST')
            ->withUri(new Uri('/api/database'))->withAddedHeader('Content-Type', 'application/json')
            ->withAddedHeader("Authorization", sprintf("Bearer %s", $jwt))->withBody($body);

        $this->assertEquals('POST', (string) $request->getMethod());
        $this->assertEquals(200, Dispatch($request)->getStatusCode());
    }

    /**
     * @brief testIsAllowedToDeleteDatabase
     * @description The test is supposed to fail with a 401.
     * @covers \redcathedral\phpMySQLAdminrest\App
     * @covers \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\RouteConfigurationProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers \redcathedral\phpMySQLAdminrest\Facades\JWTFacade
     * @covers redcathedral\phpMySQLAdminrest\Controller\AuthenticationController
     * @covers \redcathedral\phpMySQLAdminrest\Strategy\InMemoryAuthenticationStrategy
     * @covers \redcathedral\phpMySQLAdminrest\Implementations\HashSHA256
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\CloudTrailMiddleware
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\JWTAuthMiddleware
     * @covers \redcathedral\phpMySQLAdminrest\Middleware\JSONOnlyMiddleware
     * 
     * @covers \redcathedral\phpMySQLAdminrest\Controller\DatabaseController::__construct
     * @covers \redcathedral\phpMySQLAdminrest\Controller\DatabaseController::listDatabases
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin::__construct
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin::__destruct
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin::close
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin::listDatabases
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin::deleteDatabase
     * @covers \redcathedral\phpMySQLAdminrest\Controller\DatabaseController::deleteDatabase
     * @covers \redcathedral\phpMySQLAdminrest\Exception\ServerErrorException
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin::hasDatabase
     * @depends testIsAllowedToCreateDatabase
     * 
     */
    public function testIsAllowedToDeleteDatabase(): void
    {

        $databasename = "michael";
        $mysqladmin = App()->get(\redcathedral\phpMySQLAdminrest\MySQLAdmin::class);
        if (!$mysqladmin->hasDatabase($databasename)) {
            $mysqladmin->createDatabase($databasename);
        }

        $jwt = JWTFacade::encode(array(
            "aud" => "me",
            "uuid" => "64646464"
        ));

        $request = ServerRequestFactory::fromGlobals()->withMethod('DELETE')
            ->withUri(new Uri(sprintf("/api/database/%s", $databasename)))->withAddedHeader('Content-Type', 'application/json')
            ->withAddedHeader("Authorization", sprintf("Bearer %s", $jwt));

        $this->assertEquals('DELETE', (string) $request->getMethod());
        $this->assertEquals(200, Dispatch($request)->getStatusCode());
    }

    /**
     * tearDown()
     */
    public function tearDown(): void
    {
    }
}
