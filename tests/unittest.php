<?php

namespace redcathedral\tests;

use PHPUnit\Framework\TestCase;
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
    }


    /**
     * @uses \redcathedral\phpMySQLAdminrest\App
     * @uses redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin
     * @covers \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\RouterConfigurationProvider
     */
    public function testCanCreateDatabase(): void
    {
        $this->assertTrue($this->mysqladmin->createDatabase('testDatabase')->hasDatabase('testDatabase'));
    }

    /**
     * @uses \redcathedral\phpMySQLAdminrest\App
     * @uses redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin
     * @covers \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\RouterConfigurationProvider
     */
    public function testCanDeleteDatabase(): void
    {
        $this->assertFalse($this->mysqladmin->deleteDatabase('testDatabase')->hasDatabase('testDatabase'));
    }

    /**
     * @uses redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @uses \redcathedral\phpMySQLAdminrest\App
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin
     * @covers \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\RouterConfigurationProvider
     */
    public function testCanListDatabases(): void
    {
        $this->assertIsArray($this->mysqladmin->listDatabases());
    }

    /**
     * @uses \redcathedral\phpMySQLAdminrest\App
     * @uses redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin
     * @covers \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\RouterConfigurationProvider
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
     * @uses \redcathedral\phpMySQLAdminrest\App
     * @uses redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @covers \redcathedral\phpMySQLAdminrest\MySQLAdmin
     * @covers \redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @covers \redcathedral\phpMySQLAdminrest\Providers\RouterConfigurationProvider
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
     * @covers redcathedral\phpMySQLAdminrest\Providers\RouterConfigurationProvider
     */
    public function testCanGetBasicTokenFromAuthorizationHeader(): void {

        $x = new class() {
            use AuthenticationTrait;
        };

        $token = base64_encode(sprintf("%s:%s", "admin","admin"));

        $_SERVER['HTTP_AUTHORIZATION'] = sprintf("Basic %s", $token);

        $token = $x->getBasicToken();

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
        $_SERVER = null;
    }
}
