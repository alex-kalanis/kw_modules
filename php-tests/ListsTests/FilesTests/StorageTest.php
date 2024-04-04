<?php

namespace ListsTests\FilesTests;


use CommonTestClass;
use kalanis\kw_modules\ModulesLists\File\Storage;
use kalanis\kw_modules\ModuleException;
use kalanis\kw_storage\Interfaces\IStorage;
use kalanis\kw_storage\StorageException;
use Traversable;


class StorageTest extends CommonTestClass
{
    /**
     * @throws ModuleException
     */
    public function testSimple(): void
    {
        $lib = new Storage(new XStorage(), '');
        $lib->setModuleLevel(999);
        $this->assertTrue($lib->save('something'));
        $this->assertEquals('mock ok', $lib->load());
    }

    /**
     * @throws ModuleException
     */
    public function testStream(): void
    {
        $lib = new Storage(new XStreamStorage(), '');
        $lib->setModuleLevel(999);
        $this->assertTrue($lib->save('something'));
        $this->assertEquals('mock ok', $lib->load());
    }

    /**
     * @throws ModuleException
     */
    public function testDeadRead(): void
    {
        $lib = new Storage(new XDeadStorage(), '');
        $lib->setModuleLevel(999);
        $this->expectException(ModuleException::class);
        $lib->load();
    }

    /**
     * @throws ModuleException
     */
    public function testDeadWrite(): void
    {
        $lib = new Storage(new XDeadStorage(), '');
        $lib->setModuleLevel(999);
        $this->expectException(ModuleException::class);
        $lib->save('something');
    }

    /**
     * @throws ModuleException
     */
    public function testNoPath(): void
    {
        $lib = new Storage(new XStorage(), '');
        $this->expectException(ModuleException::class);
        $lib->load();
    }
}


class XStorage implements IStorage
{
    public function canUse(): bool
    {
        return true;
    }

    public function write(string $sharedKey, $data, ?int $timeout = null): bool
    {
        return true;
    }

    public function read(string $sharedKey): string
    {
        return 'mock ok';
    }

    public function remove(string $sharedKey): bool
    {
        return true;
    }

    public function exists(string $sharedKey): bool
    {
        return true;
    }

    public function lookup(string $mask): Traversable
    {
        yield from [];
    }

    public function increment(string $key): bool
    {
        return false;
    }

    public function decrement(string $key): bool
    {
        return false;
    }

    public function removeMulti(array $keys): array
    {
        return [];
    }

    public function isFlat(): bool
    {
        return true;
    }
}


class XStreamStorage extends XStorage
{
    public function read(string $sharedKey): string
    {
        return 'mock ok';
    }
}


class XDeadStorage extends XStorage
{
    public function write(string $sharedKey, $data, ?int $timeout = null): bool
    {
        throw new StorageException('mock');
    }

    public function read(string $sharedKey): string
    {
        throw new StorageException('mock');
    }
}
