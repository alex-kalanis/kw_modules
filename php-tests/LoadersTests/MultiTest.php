<?php

namespace LoadersTests;


use CommonTestClass;
use kalanis\kw_input\Interfaces\IFiltered;
use kalanis\kw_modules\Interfaces\ILoader;
use kalanis\kw_modules\Interfaces\Modules\IModule;
use kalanis\kw_modules\Loaders\ClassLoader;
use kalanis\kw_modules\Loaders\TSeparate;
use kalanis\kw_modules\ModuleException;
use kalanis\kw_modules\Output;


class MultiTest extends CommonTestClass
{
    /**
     * @throws ModuleException
     */
    public function testSimple(): void
    {
        $lib = new ClassLoader([new XLoader()]);
        $this->assertNotNull($lib->load(['test']));
        $this->assertNull($lib->load(['not exists']));
    }

    /**
     * @param string[] $in
     * @param string[][] $expected
     * @throws ModuleException
     * @dataProvider separateProvider
     */
    public function testSeparate(array $in, array $expected): void
    {
        $lib = new XSeparate();
        $this->assertEquals($expected, $lib->xSeparateModule($in));
    }

    public function separateProvider(): array
    {
        return [
            [['foo'], ['foo', 'foo']], // the class name stays the same as module name
            [['foo', 'bar'], ['foo', 'bar']], // the class name is different
            [['foo', 'bar', 'baz'], ['foo', 'bar\\baz']], // the class name is inside different path
        ];
    }

    /**
     * @throws ModuleException
     */
    public function testSeparateFail(): void
    {
        $lib = new XSeparate();
        $this->expectException(ModuleException::class);
        $lib->xSeparateModule([]); // nothing set
    }
}


class XSeparate
{
    use TSeparate;

    /**
     * @param array $path
     * @param string|null $emptyDefault
     * @throws ModuleException
     * @return string[]
     */
    public function xSeparateModule(array $path, ?string $emptyDefault = null): array
    {
        return $this->separateModule($path, $emptyDefault);
    }
}


class XLoader implements ILoader
{
    public function load(array $path, array $constructParams = []): ?IModule
    {
        return 'test' == strval(reset($path)) ? new XModule() : null ;
    }
}


class XModule implements IModule
{
    public function init(IFiltered $inputs, array $passedParams): void
    {
        // nothing to do
    }

    public function process(): void
    {
        // nothing to do
    }

    public function output(): Output\AOutput
    {
        return new Output\Raw();
    }
}
