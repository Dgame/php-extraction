<?php

use PHPUnit\Framework\TestCase;
use function Dgame\Extraction\export;

/**
 * Class ExportTest
 */
final class ExportTest extends TestCase
{
    public function testExportEmptyWithNoFields(): void
    {
        $this->assertEmpty(export()->from([]));
    }

    public function testExportWithNoFields(): void
    {
        $this->assertEmpty(export()->from(['foo' => 'bar']));
    }

    public function testExportExact(): void
    {
        $this->assertEquals(['foo' => 'bar'], export('foo')->from(['foo' => 'bar']));
    }

    public function testExport(): void
    {
        $this->assertEquals(['foo' => 'bar'], export('foo')->from(['foo' => 'bar', 'bar' => 'foo']));
    }

    public function testExportWithDefaults(): void
    {
        $this->assertEmpty(export()->defaults(['foo' => 42])->from([]));
        $this->assertEquals(['foo' => null], export('foo')->from([]));
        $this->assertEquals(['foo' => 42], export('foo')->defaults(['foo' => 42])->from([]));
        $this->assertEquals(['foo' => 42], export('foo')->defaults(['foo' => 42])->from(['bar' => 23]));
    }

    public function testExtractExport(): void
    {
        ['name' => $name, 'password' => $pw] = export('name', 'password')->from(['name' => 'Max', 'password' => 'test', 'foo' => 42]);
        $this->assertEquals('Max', $name);
        $this->assertEquals('test', $pw);
    }
}