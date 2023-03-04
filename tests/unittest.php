<?php

namespace redcathedral\tests;

use PHPUnit\Framework\TestCase;
use function redcathedral\phpMySQLAdminrest\App;

final class UnitTest extends TestCase
{
    private $mysqladmin;


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
     * @covers \MySQLAdmin::createDatabase
     */
    public function testCanCreateDatabase(): void
    {
        $this->assertTrue($this->mysqladmin->createDatabase('testDatabase')->hasDatabase('testDatabase'));
    }

    /**
     * @covers \MySQLAdmin::deleteDatabase
     */
    public function testCanDeleteDatabase(): void
    {
        $this->assertFalse($this->mysqladmin->deleteDatabase('testDatabase')->hasDatabase('testDatabase'));
    }

    public function tearDown(): void
    {
        $this->mysqladmin->close();
    }
}
