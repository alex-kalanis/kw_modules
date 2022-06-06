<?php

namespace ProcessingTests;


use CommonTestClass;
use kalanis\kw_modules\ModuleException;
use kalanis\kw_modules\Processing\Storage\Storage;
use kalanis\kw_storage\StorageException;


class StorageTest extends CommonTestClass
{
    /**
     * @throws ModuleException
     */
    public function testSimple(): void
    {
        $lib = new Storage(new \XStorage(), '__');
        $lib->setModuleLevel(0);
        $this->assertEmpty($lib->load());
        $lib->save('abc-def-ghi-jkl');
        $this->assertEquals('abc-def-ghi-jkl', $lib->load());
    }

    /**
     * @throws ModuleException
     */
    public function testNoPath(): void
    {
        $lib = new Storage(new \XStorage(), '__');
        $this->expectException(ModuleException::class);
        $this->expectExceptionMessage('Site part and then file name is not set!');
        $lib->load();
    }

    /**
     * @throws ModuleException
     */
    public function testNoStorageLoad(): void
    {
        $lib = new Storage(new XFStorage(), '__');
        $lib->setModuleLevel(0);
        $this->expectException(ModuleException::class);
        $this->expectExceptionMessage('Problem with storage load');
        $lib->load();
    }

    /**
     * @throws ModuleException
     */
    public function testNoStorageSave(): void
    {
        $lib = new Storage(new XFStorage(), '__');
        $lib->setModuleLevel(0);
        $this->expectException(ModuleException::class);
        $this->expectExceptionMessage('Problem with storage save');
        $lib->save('');
    }
}


class XFStorage extends \XStorage
{
    public function load(string $key)
    {
        throw new StorageException('dummy');
    }

    public function save(string $key, $data, ?int $timeout = null): bool
    {
        throw new StorageException('dummy');
    }
}
