<?php

namespace LoadersTests;


use CommonTestClass;
use kalanis\kw_input\Interfaces\IVariables;
use kalanis\kw_modules\Interfaces\ILoader;
use kalanis\kw_modules\Interfaces\IModule;
use kalanis\kw_modules\Loaders\MultiLoader;
use kalanis\kw_modules\ModuleException;
use kalanis\kw_modules\Output;


class MultiTest extends CommonTestClass
{
    /**
     * @throws ModuleException
     */
    public function testSimple(): void
    {
        $lib = MultiLoader::init();
        $lib->addLoader(new XLoader());
        $this->assertNotNull($lib->load('test'));
        $this->assertNull($lib->load('not exists'));
    }
}


class XLoader implements ILoader
{
    public function load(string $module, ?string $constructPath = null, array $constructParams = []): ?IModule
    {
        return 'test' == $module ? new XModule() : null ;
    }
}


class XModule implements IModule
{
    public function init(IVariables $inputs, array $passedParams): void
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
