<?php

namespace MixerTests\PassedParams;


use CommonTestClass;
use kalanis\kw_input\Interfaces\IFiltered;
use kalanis\kw_modules\Interfaces\IModule;
use kalanis\kw_modules\Mixer;
use kalanis\kw_modules\ModuleException;
use kalanis\kw_modules\Output;


class FactoryTest extends CommonTestClass
{
    /**
     * @param $param
     * @throws ModuleException
     * @dataProvider passProvider
     */
    public function testPass($param): void
    {
        $lib = new Mixer\PassedParams\Factory();
        $this->assertInstanceOf(Mixer\PassedParams\APassedParam::class, $lib->getClass($param));
    }

    public function passProvider(): array
    {
        return [
            [new XModuleSolo()],
            [new XModuleSetOkay()],
            [new XModuleSetMapped()],
        ];
    }

    /**
     * @param mixed $param
     * @throws ModuleException
     * @dataProvider failProvider
     */
    public function testFail($param): void
    {
        $lib = new XFailFactory();
        $this->expectException(ModuleException::class);
        $lib->getClass($param);
    }

    public function failProvider(): array
    {
        return [
            [new XModuleSolo()],
            [new XModuleSetFailed()],
        ];
    }
}


class XFailFactory extends Mixer\PassedParams\Factory
{
    protected array $map = [
        // nothing here
    ];
}


class XModuleSolo implements IModule
{
    public function init(IFiltered $inputs, array $passedParams): void
    {
    }

    public function process(): void
    {
    }

    public function output(): Output\AOutput
    {
        return new Output\Raw();
    }
}


class XModuleSetOkay implements IModule
{
    public function init(IFiltered $inputs, array $passedParams): void
    {
    }

    public function process(): void
    {
    }

    public function output(): Output\AOutput
    {
        return new Output\Raw();
    }

    public function passParamsAs(): string
    {
        return Mixer\PassedParams\SingleParam::class;
    }
}


class XModuleSetMapped implements IModule
{
    public function init(IFiltered $inputs, array $passedParams): void
    {
    }

    public function process(): void
    {
    }

    public function output(): Output\AOutput
    {
        return new Output\Raw();
    }

    public function passParamsAs(): string
    {
        return 'single';
    }
}


class XModuleSetFailed implements IModule
{
    public function init(IFiltered $inputs, array $passedParams): void
    {
    }

    public function process(): void
    {
    }

    public function output(): Output\AOutput
    {
        return new Output\Raw();
    }

    public function passParamsAs(): string
    {
        return 'not-in-class-or-map';
    }
}
