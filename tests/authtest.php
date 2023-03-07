<?php

namespace redcathedral\tests;

use PHPUnit\Framework\TestCase;
use \redcathedral\phpMySQLAdminrest\Implementations\FileAuthenticationImpl;
use redcathedral\phpMySQLAdminrest\Implementations\HashSHA256;

use function redcathedral\phpMySQLAdminrest\App;

final class AuthTest extends TestCase
{

    /**
     * @brief test FileAuthenticationImpl
     * @description Test the verify-function of a FileAuthenticationClass.
     * @covers \redcathedral\phpMySQLAdminrest\Implementations\FileAuthenticationImpl
     * @covers \redcathedral\phpMySQLAdminrest\Implementations\HashSHA256
     * 
     * @uses redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @uses redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @uses \redcathedral\phpMySQLAdminrest\Strategy\AuthenticationStrategy
     */
    public function testFileAuthenticationImpl(): void
    {
        // Fetch the 
        $username = 'admin';
        $auth = App()->get(\redcathedral\phpMySQLAdminrest\Implementations\FileAuthenticationImpl::class);

        $auth->addUser($username, HashSHA256::fromString($username)); // Adds admin:admin

        // Query against the interface
        $this->assertTrue($auth->verify($username, HashSHA256::fromString($username)));
    }

    /**
     * @brief test testNotFoundFileAuthenticationImpl
     * @description Test the verify-function of a FileAuthenticationClass. It is expected to FAIL.
     * @covers \redcathedral\phpMySQLAdminrest\Implementations\FileAuthenticationImpl
     * @covers \redcathedral\phpMySQLAdminrest\Implementations\HashSHA256
     * 
     * @uses redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @uses redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @uses \redcathedral\phpMySQLAdminrest\Strategy\AuthenticationStrategy
     */
    public function testNotFoundFileAuthenticationImpl(): void
    {
        // Fetch the 
        $auth = App()->get(\redcathedral\phpMySQLAdminrest\Implementations\FileAuthenticationImpl::class);

        $username = 'admin';
        $hash = HashSHA256::fromString($username);
        $auth->addUser($username, $hash); // Adds admin:admin

        // Query against the interface
        $this->assertFalse($auth->verify('test', $hash));
    }

     /**
     * @brief test FileAuthenticationImpl
     * @description Test the verify-function of a FileAuthenticationClass.
     * @covers \redcathedral\phpMySQLAdminrest\Implementations\FileAuthenticationImpl
     * @covers \redcathedral\phpMySQLAdminrest\Implementations\HashSHA256
     * 
     * @uses redcathedral\phpMySQLAdminrest\Providers\JWTAuthenticateProvider
     * @uses redcathedral\phpMySQLAdminrest\Providers\MySQLConfigurationBootableProvider
     * @uses \redcathedral\phpMySQLAdminrest\Strategy\AuthenticationStrategy
     */
    public function testFileAuthenticationImplWithHashes(): void
    {
        // Fetch the 
        $username = 'admin';
        $auth = App()->get(\redcathedral\phpMySQLAdminrest\Implementations\FileAuthenticationImpl::class);

        $hash = hash('sha256',  $username);
        $auth->addUser($username, HashSHA256::fromHash($hash)); // Adds admin:admin

        // Query against the interface
        $this->assertTrue($auth->verify($username, HashSHA256::fromHash($hash)));
    }
}
