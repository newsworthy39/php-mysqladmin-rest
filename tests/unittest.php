<?php

namespace redcathedral\tests;

use Laminas\Diactoros\ServerRequestFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use redcathedral\phpMySQLAdminrest\Controller\AuthenticationController;
use \redcathedral\phpMySQLAdminrest\Facades\JWTFacade;
use redcathedral\phpMySQLAdminrest\Traits\AuthenticationTrait;
use stdClass;

use function redcathedral\phpMySQLAdminrest\App;

final class UnitTest extends TestCase
{
    private $mysqladmin;

  

    /**
     * setUp()
     */
    public function setUp(): void
    {
        try {
            $this->mysqladmin = App()->get(\redcathedral\phpMySQLAdminrest\MySQLAdmin::class);
            $this->assertIsObject($this->mysqladmin);
        } catch (\League\Container\Exception\NotFoundException $ex) {
            $this->assertFalse(true);
        }

        $_SERVER = array(); // Fix for ServerRequestFactory
    }


    /**
     * @covers \redcathedral\phpMySQLAdminrest\App
     * @covers redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin
     * @covers \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\RouteConfigurationProvider
     */
    public function testCanCreateDatabase(): void
    {
        $this->assertTrue($this->mysqladmin->createDatabase('testDatabase')->hasDatabase('testDatabase'));
    }

    /**
     * @covers \redcathedral\phpMySQLAdminrest\App
     * @covers redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin
     * @covers \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\RouteConfigurationProvider
     */
    public function testCanDeleteDatabase(): void
    {
        $this->assertFalse($this->mysqladmin->deleteDatabase('testDatabase')->hasDatabase('testDatabase'));
    }

    /**
     * @covers redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers \redcathedral\phpMySQLAdminrest\App
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin
     * @covers \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\RouteConfigurationProvider
     */
    public function testCanListDatabases(): void
    {
        $this->assertIsArray($this->mysqladmin->listDatabases());
    }

    /**
     * @covers \redcathedral\phpMySQLAdminrest\App
     * @covers redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin
     * @covers \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\RouteConfigurationProvider
     * @covers \redcathedral\phpMySQLAdminrest\Facades\JWTFacade
     */
    public function testJWTCanVerify(): void
    {
        $uuid = "64646464";
        $jwt = JWTFacade::encode(array(
            "aud" => "me",
            "uuid" => $uuid
        ));

        $payload = JWTFacade::verify($jwt);

        $this->assertSame(JWTFacade::getIssuer(), $payload->iss);
        $this->assertSame($uuid, $payload->uuid);
    }

    /**
     * @covers \redcathedral\phpMySQLAdminrest\App
     * @covers redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin
     * @covers \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\RouteConfigurationProvider
     * @covers \redcathedral\phpMySQLAdminrest\Facades\JWTFacade
     */
    public function testJWTCanDecode(): void
    {
        $uuid = "64646464";
        $jwt = JWTFacade::encode(array(
            "aud" => "me",
            "uuid" => $uuid
        ));

        // array($plainHeader, $plainPayload);
        list($header, $payload) = JWTFacade::decode($jwt);

        $this->assertSame(JWTFacade::getIssuer(), $payload->iss);
        $this->assertSame($uuid, $payload->uuid);
    }

    /**
     * @covers \redcathedral\phpMySQLAdminrest\Traits\AuthenticationTrait
     * @covers redcathedral\phpMySQLAdminrest\MySQLAdmin
     * @covers redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @covers redcathedral\phpMySQLAdminrest\Providers\RouteConfigurationProvider
     */
    public function testCanGetBasicTokenFromAuthorizationHeader(): void {
        $x = new class() {
            use AuthenticationTrait;

            public function getBBasicToken(ServerRequestInterface $request) {
                return $this->getBasicToken($request);
            }
        };

        $token = base64_encode(sprintf("%s:%s", "admin","admin"));
        $request = ServerRequestFactory::fromGlobals()->withAddedHeader("Authorization", sprintf("Basic %s", $token));

        $this->assertEquals($token, $x->getBBasicToken($request));


        list($username, $password) = explode(":", base64_decode($token));

        $this->assertSame($username, "admin");
        $this->assertSame($password, "admin");
    }

    /**
     * tearDown()
     */
    public function tearDown(): void
    {
        $this->mysqladmin->close();
    }
}
