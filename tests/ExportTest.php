<?php

use Dgame\Ensurance\Exception\EnsuranceException;
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
        [
            'name'     => $name,
            'password' => $pw
        ] = export('name', 'password')->from(
            [
                'name'     => 'Max',
                'password' => 'test',
                'foo'      => 42
            ]
        );

        $this->assertEquals('Max', $name);
        $this->assertEquals('test', $pw);
    }

    public function testRequire(): void
    {
        $this->expectException(EnsuranceException::class);
        $this->expectExceptionMessage('Field "password" is required');

        [
            'name'     => $name,
            'password' => $pw
        ] = export('name', 'password')
            ->require('password')
            ->from(['name' => 'Max']);

        $this->assertEquals('Max', $name);
        $this->assertNull($pw);
    }

    public function testOrFailWith(): void
    {
        $this->expectException(EnsuranceException::class);
        $this->expectExceptionMessage('Wir benötigen ein Passwort');

        [
            'name'     => $name,
            'password' => $pw
        ] = export('name', 'password')->orFailWith(
            [
                'password' => 'Wir benötigen ein Passwort'
            ]
        )->from(['name' => 'Max']);

        $this->assertEquals('Max', $name);
        $this->assertNull($pw);
    }

    public function testRequireAllWithOneMissingField(): void
    {
        $this->expectException(EnsuranceException::class);
        $this->expectExceptionMessage('Field "password" is required');

        [
            'name'     => $name,
            'password' => $pw
        ] = export('name', 'password')->requireAll()->from(['name' => 'Max']);

        $this->assertEquals('Max', $name);
        $this->assertNull($pw);
    }

    public function testRequireAllWithMissingFields(): void
    {
        $this->expectException(EnsuranceException::class);
        $this->expectExceptionMessage('Field "name" is required'); // First comes first serves

        [
            'name'     => $name,
            'password' => $pw
        ] = export('name', 'password')->requireAll()->from([]);

        $this->assertNull($name);
        $this->assertNull($pw);
    }
}
