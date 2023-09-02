<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_modules\Support;
use kalanis\kw_paths\PathsException;
use kalanis\kw_paths\Stuff;


class SupportTest extends CommonTestClass
{
    /**
     * @param string $in
     * @param string[] $expected
     * @throws PathsException
     * @dataProvider pathFromDirProvider
     */
    public function testPathFromDir(string $in, array $expected): void
    {
        $this->assertEquals($expected, Support::modulePathFromDirPath(Stuff::linkToArray($in)));
    }

    public function pathFromDirProvider(): array
    {
        return [
            ['/foo/../bar-baz/.', ['Foo', 'BarBaz']],
        ];
    }

    /**
     * @param string $in
     * @param string[] $expected
     * @dataProvider nameFromTemplateProvider
     */
    public function testPathFromTemplate(string $in, array $expected): void
    {
        $this->assertEquals($expected, Support::modulePathFromTemplate($in));
        $this->assertEquals($in, Support::templatePathForModule($expected));
    }

    public function nameFromTemplateProvider(): array
    {
        return [
            ['WHATEVER_FOR_NAME', ['WhateverForName']],
            ['WHATEVER__FOR__NAME', ['Whatever', 'For', 'Name']],
        ];
    }

    /**
     * @param string $in
     * @param string $expected
     * @dataProvider clearNameProvider
     */
    public function testClearName(string $in, string $expected): void
    {
        $this->assertEquals($expected, Support::clearModuleName($in));
    }

    public function clearNameProvider(): array
    {
        return [
            ['!whatever/\for/\name', 'whatever\for\name'],
            ['\whatever--for--name', '\whatever--for--name'],
        ];
    }

    /**
     * @param string $in
     * @param string $expected
     * @dataProvider normalizeModuleProvider
     */
    public function testNormalizeModule(string $in, string $expected): void
    {
        $this->assertEquals($expected, Support::normalizeModuleName($in));
    }

    public function normalizeModuleProvider(): array
    {
        return [
            ['foo-bar-baz', 'FooBarBaz'],
        ];
    }

    /**
     * @param string $in
     * @param string $expected
     * @dataProvider normalizeTemplateProvider
     */
    public function testTemplateModule(string $in, string $expected): void
    {
        $this->assertEquals($expected, Support::templateModuleName($in));
    }

    public function normalizeTemplateProvider(): array
    {
        return [
            ['FooBar0Baz', 'FOO_BAR0_BAZ'],
            ['Nope-Yep', 'NOPE_YEP'],
            ['Αθήνα', 'ΑΘΉΝΑ'], // not ASCII
        ];
    }
}
