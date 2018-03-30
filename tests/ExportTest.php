<?php

use function Dgame\Extraction\export;
use PHPUnit\Framework\TestCase;

/**
 * Class ExportTest
 */
final class ExportTest extends TestCase
{
    public function testExportEmptyWithNoFields()
    {
        $this->assertEmpty(export()->from([]));
    }

    public function testExportWithNoFields()
    {
        $this->assertEmpty(export()->from(['foo' => 'bar']));
    }

    public function testExportExact()
    {
        $this->assertEquals(['foo' => 'bar'], export('foo')->from(['foo' => 'bar']));
    }

    public function testExport()
    {
        $this->assertEquals(['foo' => 'bar'], export('foo')->from(['foo' => 'bar', 'bar' => 'foo']));
    }

    public function testExportWithDefaults()
    {
        $this->assertEmpty(export()->defaults(['foo' => 42])->from([]));
        $this->assertEquals(['foo' => null], export('foo')->from([]));
        $this->assertEquals(['foo' => 42], export('foo')->defaults(['foo' => 42])->from([]));
        $this->assertEquals(['foo' => 42], export('foo')->defaults(['foo' => 42])->from(['bar' => 23]));
    }

    public function testExtractExport()
    {
        ['name' => $name, 'password' => $pw] = export('name', 'password')->from(['name' => 'Max', 'password' => 'test', 'foo' => 42]);
        $this->assertEquals('Max', $name);
        $this->assertEquals('test', $pw);
    }
}