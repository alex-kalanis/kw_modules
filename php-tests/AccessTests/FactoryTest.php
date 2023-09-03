<?php

namespace AccessTests;


use CommonTestClass;
use kalanis\kw_files\FilesException;
use kalanis\kw_files\Interfaces\IProcessFiles;
use kalanis\kw_modules\Access\Factory;
use kalanis\kw_modules\Interfaces\ILoader;
use kalanis\kw_modules\Interfaces\IModule;
use kalanis\kw_modules\Interfaces\Lists\IModulesList;
use kalanis\kw_modules\Mixer\Processor;
use kalanis\kw_modules\ModuleException;
use kalanis\kw_modules\ModulesLists\Record;


class FactoryTest extends CommonTestClass
{
    /**
     * @param $param
     * @throws ModuleException
     * @dataProvider passProvider
     */
    public function testPass($param): void
    {
        $lib = new Factory();
        $this->assertInstanceOf(Processor::class, $lib->getProcessor($param));
    }

    public function passProvider(): array
    {
//        $storage = new Storage(new DefaultKey(), new Memory());
        return [
            [['modules_loaders' => new XLoader(), 'modules_source' => new XSourceList()]],
            [['modules_loaders' => [
                'api', 'admin', 'web', // string select
                XLoader::class, // known class
                123 // will be skipped - this one fails
            ], 'modules_source' => new XSourceList()]],
            [['modules_loaders' => new XLoader(), 'modules_source' => new \XStorage()]],
            [['modules_loaders' => new XLoader(), 'modules_source' => new XProcFiles()]],
            [['modules_loaders' => new XLoader(), 'modules_source' => XSourceList::class]],
            [['modules_loaders' => new XLoader(), 'modules_param_format' => 'serialize', 'storage_path' => 'somewhere']],
            [['modules_loaders' => new XLoader(), 'modules_param_format' => 'serial', 'storage_path' => 'somewhere']],
            [['modules_loaders' => new XLoader(), 'modules_param_format' => 's', 'storage_path' => 'somewhere']],
            [['modules_loaders' => new XLoader(), 'modules_param_format' => 'json', 'volume_path' => 'somewhere']],
            [['modules_loaders' => new XLoader(), 'modules_param_format' => 'js', 'volume_path' => 'somewhere']],
            [['modules_loaders' => new XLoader(), 'modules_param_format' => 'j', 'volume_path' => 'somewhere']],
            [['modules_loaders' => new XLoader(), 'modules_param_format' => 'http', 'volume_path' => 'somewhere']],
            [['modules_loaders' => new XLoader(), 'modules_param_format' => 'web', 'volume_path' => 'somewhere']],
            [['modules_loaders' => new XLoader(), 'modules_param_format' => 'w', 'volume_path' => 'somewhere']],
        ];
    }

    /**
     * @param mixed $param
     * @throws ModuleException
     * @dataProvider failProvider
     */
    public function testFail($param): void
    {
        $lib = new Factory();
        $this->expectException(ModuleException::class);
        $lib->getProcessor($param);
    }

    public function failProvider(): array
    {
        return [
            [true],
            [false],
            [null],
            [123],
            ['somewhere'],
            [new \stdClass()],
            [['modules_loaders' => new XLoader(), 'modules_param_format' => 'serialize', 'files_path' => ['somewhere']]], // failing due non-existent path
            [['modules_loaders' => new XLoader(), 'modules_param_format' => 'json', 'storage_path' => 'somewhere', 'storage_key' => new \stdClass()]], // failing due bad class
            [['modules_loaders' => new XLoader(), 'modules_source' => \stdClass::class]], // bad class - not interface
            [['modules_loaders' => new XLoader(), 'modules_source' => 'somewhere']], // not a class, cannot target that way
        ];
    }
}


class XLoader implements ILoader
{
    public function load(array $module, array $constructParams = []): ?IModule
    {
        throw new ModuleException('mock die');
    }
}


class XSourceList implements IModulesList
{
    public function setModuleLevel(int $level): void
    {
        // nothing
    }

    public function add(string $moduleName, bool $enabled = false, array $params = []): bool
    {
        return true;
    }

    public function get(string $moduleName): ?Record
    {
        throw new ModuleException('mock die');
    }

    public function listing(): array
    {
        throw new ModuleException('mock die');
    }

    public function updateBasic(string $moduleName, ?bool $enabled, ?array $params): bool
    {
        return false;
    }

    public function updateObject(Record $record): bool
    {
        return false;
    }

    public function remove(string $moduleName): bool
    {
        return false;
    }
}


class XProcFiles implements IProcessFiles
{
    public function saveFile(array $entry, $content, ?int $offset = null): bool
    {
        return false;
    }

    public function readFile(array $entry, ?int $offset = null, ?int $length = null)
    {
        throw new FilesException('mock die');
    }

    public function copyFile(array $source, array $dest): bool
    {
        return false;
    }

    public function moveFile(array $source, array $dest): bool
    {
        return false;
    }

    public function deleteFile(array $entry): bool
    {
        return false;
    }
}
