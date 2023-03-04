<?php

namespace redcathedral\tests;

use redcathedral\phpmysqladminrest\mysqladmin;
use PHPUnit\Framework\TestCase;

final class UnitTest extends TestCase {

    private $mysqladmin;

    public function setUp() : void {
        $this->mysqladmin = new mysqladmin();
    }
    public function testCanCreateDatabase() : void {
        
        $this->mysqladmin->createDatabase('testDatabase');
        
        $this->assertTrue($this->mysqladmin->hasDatabase('testDatabase'));
        $this->assertFalse(!$this->mysqladmin->hasDatabase('testDatabase'));
    }

    public function tearDown() : void {
        $this->mysqladmin->deleteDatabase('testDatabase');
    }
}