<?php

namespace MixerTests;


use CommonTestClass;
use kalanis\kw_input\Filtered\SimpleArrays;
use kalanis\kw_input\Interfaces\IFiltered;
use kalanis\kw_modules\Interfaces;
use kalanis\kw_modules\Mixer\Processor;
use kalanis\kw_modules\ModuleException;
use kalanis\kw_modules\ModulesLists\Record;
use kalanis\kw_modules\Output;


class ProcessorTest extends CommonTestClass
{
    /**
     * @throws ModuleException
     */
    public function testNothing(): void
    {
        $lib = $this->getLib();
        $this->assertEquals('blablabla  blablabla', $lib->fill('blablabla  blablabla', $this->getFilter(), 99));
    }

    /**
     * @throws ModuleException
     */
    public function testSimple(): void
    {
        $lib = $this->getLib();
        $content = 'blablabla {MODULE/} blablabla {OTHER_DATA/} blablabla {ANOTHER__DATA/} blablabla {CHANGES-LEFT/}';

        $this->assertEquals(
            'blablabla dummy1 blablabla {OTHER_DATA/} blablabla dummy2 blablabla {CHANGES-LEFT/}',
            $lib->fill($content, $this->getFilter(), 99)
        );
    }

    /**
     * @throws ModuleException
     */
    public function testWithParamsAndNotUse(): void
    {
        $lib = $this->getLib();
        $content = 'blablabla{MODULE}foo=bar&abc=def&ghi=12.5{/MODULE}blablabla {NOT_USE}bar=ghi{/NOT_USE} blablabla';

        $this->assertEquals(
            'blablabladummy1blablabla  blablabla',
            $lib->fill($content, $this->getFilter(), 99)
        );
    }

    protected function getLib(): Processor
    {
        return new Processor(new XLoader(), new XModulesList());
    }

    protected function getFilter(): IFiltered
    {
        return new SimpleArrays([]);
    }
}


class XLoader implements Interfaces\ILoader
{
    public function load(array $module, array $constructParams = []): ?Interfaces\IModule
    {
        $which = strval(reset($module));
        switch ($which) {
            case 'Module':
                return new DummyOne();
            case 'Another':
                return new DummyTwo();
            case 'NotUse':
                return new DummyThree();
            default:
                throw new ModuleException('test fail');
        }
    }
}


class DummyOne implements Interfaces\IModule
{
    public function init(IFiltered $inputs, array $passedParams): void
    {
        // nothing need
    }

    public function process(): void
    {
        // nothing need
    }

    public function output(): Output\AOutput
    {
        return (new Output\Raw())->setContent('dummy1');
    }
}


class DummyTwo implements Interfaces\IModule
{
    public function init(IFiltered $inputs, array $passedParams): void
    {
        // nothing need
    }

    public function process(): void
    {
        // nothing need
    }

    public function output(): Output\AOutput
    {
        return (new Output\Raw())->setContent('dummy2');
    }
}


class DummyThree implements Interfaces\IModule
{
    public function init(IFiltered $inputs, array $passedParams): void
    {
        // nothing need
    }

    public function process(): void
    {
        // nothing need
    }

    public function output(): Output\AOutput
    {
        return (new Output\Raw())->setContent('dummy3');
    }
}


class XModulesList implements Interfaces\Lists\IModulesList
{
    public function setModuleLevel(int $level): void
    {
        // ignore
    }

    public function add(string $moduleName, bool $enabled = false, array $params = []): bool
    {
        return false;
    }

    public function get(string $moduleName): ?\kalanis\kw_modules\ModulesLists\Record
    {
        return null;
    }

    public function listing(): array
    {
        $use = [];

        $m1 = new Record();
        $m1->setModuleName('Module');
        $m1->setEnabled(true);
        $use['Module'] = $m1;

        $m2 = new Record();
        $m2->setModuleName('Another');
        $m2->setEnabled(true);
        $use['Another'] = $m2;

        $m3 = new Record();
        $m3->setModuleName('NotUse');
        $m3->setEnabled(false);
        $use['NotUse'] = $m3;

        return $use;
    }

    public function updateBasic(string $moduleName, ?bool $enabled, ?array $params): bool
    {
        return false;
    }

    public function updateObject(\kalanis\kw_modules\ModulesLists\Record $record): bool
    {
        return false;
    }

    public function remove(string $moduleName): bool
    {
        return false;
    }
}
