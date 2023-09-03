<?php

namespace ListsTests\FilesTests;


use CommonTestClass;
use kalanis\kw_modules\ModuleException;
use kalanis\kw_modules\ModulesLists\File\Volume;


class VolumeTest extends CommonTestClass
{
    protected function setUp(): void
    {
        $this->clearData();
    }

    protected function tearDown(): void
    {
        $this->clearData();
    }

    protected function clearData(): void
    {
        $this->rmFile('modules.999.conf');
    }

    protected function rmFile(string $path): void
    {
        if (is_file($this->getTestPath() . DIRECTORY_SEPARATOR . $path)) {
            unlink($this->getTestPath() . DIRECTORY_SEPARATOR . $path);
        }
    }

    /**
     * @throws ModuleException
     */
    public function testSimple(): void
    {
        $lib = $this->getLib();
        $lib->setModuleLevel(999);
        $this->assertTrue($lib->save('something'));
        $this->assertEquals('something', $lib->load());
    }

    /**
     * @throws ModuleException
     */
    public function testNoPath(): void
    {
        $lib = $this->getLib();
        $this->expectException(ModuleException::class);
        $lib->load();
    }

    protected function getLib(): Volume
    {
        return new Volume($this->getTestPath());
    }

    protected function getTestPath(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data';
    }
}
