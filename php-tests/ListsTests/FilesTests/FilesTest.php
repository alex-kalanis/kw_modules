<?php

namespace ListsTests\FilesTests;


use CommonTestClass;
use kalanis\kw_files\FilesException;
use kalanis\kw_files\Interfaces\IProcessFiles;
use kalanis\kw_modules\ModulesLists\File\Files;
use kalanis\kw_modules\ModuleException;


class FilesTest extends CommonTestClass
{
    /**
     * @throws ModuleException
     */
    public function testSimple(): void
    {
        $lib = new Files(new XFiles(), []);
        $lib->setModuleLevel(999);
        $this->assertTrue($lib->save('something'));
        $this->assertEquals('mock ok', $lib->load());
    }

    /**
     * @throws ModuleException
     */
    public function testStream(): void
    {
        $lib = new Files(new XStreamFiles(), []);
        $lib->setModuleLevel(999);
        $this->assertTrue($lib->save('something'));
        $this->assertEquals('mock ok', $lib->load());
    }

    /**
     * @throws ModuleException
     */
    public function testDeadRead(): void
    {
        $lib = new Files(new XDeadFiles(), []);
        $lib->setModuleLevel(999);
        $this->expectException(ModuleException::class);
        $lib->load();
    }

    /**
     * @throws ModuleException
     */
    public function testDeadWrite(): void
    {
        $lib = new Files(new XDeadFiles(), []);
        $lib->setModuleLevel(999);
        $this->expectException(ModuleException::class);
        $lib->save('something');
    }

    /**
     * @throws ModuleException
     */
    public function testNoPath(): void
    {
        $lib = new Files(new XFiles(), []);
        $this->expectException(ModuleException::class);
        $lib->load();
    }
}


class XFiles implements IProcessFiles
{
    public function saveFile(array $entry, string $content, ?int $offset = null, int $mode = 0): bool
    {
        return true;
    }

    public function readFile(array $entry, ?int $offset = null, ?int $length = null): string
    {
        return 'mock ok';
    }

    public function copyFile(array $source, array $dest): bool
    {
        return true;
    }

    public function moveFile(array $source, array $dest): bool
    {
        return true;
    }

    public function deleteFile(array $entry): bool
    {
        return true;
    }
}


class XStreamFiles extends XFiles
{
    public function readFile(array $entry, ?int $offset = null, ?int $length = null): string
    {
        return 'mock ok';
    }
}


class XDeadFiles extends XFiles
{
    public function saveFile(array $entry, string $content, ?int $offset = null, int $mode = 0): bool
    {
        throw new FilesException('mock');
    }

    public function readFile(array $entry, ?int $offset = null, ?int $length = null): string
    {
        throw new FilesException('mock');
    }
}
