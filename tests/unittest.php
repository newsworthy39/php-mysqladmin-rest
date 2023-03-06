<?php

namespace redcathedral\tests;

use PHPUnit\Framework\TestCase;
use \redcathedral\phpMySQLAdminrest\Facades\JWTFacade;
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
    public function testJWT(): void
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
     * tearDown()
     */
    public function tearDown(): void
    {
        $this->mysqladmin->close();
    }
}
