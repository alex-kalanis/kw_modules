<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_modules\Support;


class SupportTest extends CommonTestClass
{
    /**
     * @param string $in
     * @param array $expected
     * @dataProvider paramsArrayProvider
     */
    public function testParamsArray(string $in, array $expected): void
    {
        $this->assertEquals($expected, Support::paramsIntoArray($in));
    }

    public function paramsArrayProvider(): array
    {
        return [
            ['foo=bar', ['foo' => 'bar']],
            ['foo=bar&baz=eab', ['foo' => 'bar', 'baz' => 'eab']],
        ];
    }

    /**
     * @param array $in
     * @param string $expected
     * @dataProvider paramsStringProvider
     */
    public function testParamsString(array $in, string $expected): void
    {
        $this->assertEquals($expected, Support::paramsIntoString($in));
    }

    public function paramsStringProvider(): array
    {
        return [
            [ ['foo' => 'bar'], 'foo=bar' ],
            [ ['foo' => 'bar', 'baz' => 'eab'], 'foo=bar&baz=eab' ],
        ];
    }

    /**
     * @param string $in
     * @param string $expected
     * @dataProvider normalizeNamespacedProvider
     */
    public function testNormalizeNamespaced(string $in, string $expected): void
    {
        $this->assertEquals($expected, Support::normalizeNamespacedName($in));
    }

    public function normalizeNamespacedProvider(): array
    {
        return [
            ['/foo/../bar-baz/.', 'Foo\BarBaz'],
        ];
    }

    /**
     * @param string $in
     * @param string[] $expected
     * @dataProvider nameFromTemplateProvider
     */
    public function testNameFromTemplate(string $in, array $expected): void
    {
        $this->assertEquals($expected, Support::moduleNameFromTemplate($in));
    }

    public function nameFromTemplateProvider(): array
    {
        return [
            ['whatever-for-name', ['WhateverForName']],
            ['whatever--for--name', ['Whatever', 'For', 'Name']],
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

    /**
     * @param string $in
     * @param string $expected
     * @dataProvider normalizeLinkProvider
     */
    public function testLinkModule(string $in, string $expected): void
    {
        $this->assertEquals($expected, Support::linkModuleName($in));
    }

    public function normalizeLinkProvider(): array
    {
        return [
            ['FooBar0Baz', 'foo-bar0-baz'],
            ['Nope-Yep', 'nope-yep'],
            ['Θεσσαλονίκη', 'θεσσαλονίκη'], // not ASCII
        ];
    }
}
