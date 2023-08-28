<?php

namespace ListsTests;


use CommonTestClass;
use kalanis\kw_modules\ModuleException;
use kalanis\kw_modules\ModulesLists\File;
use kalanis\kw_modules\ModulesLists\ParamsFormat;
use kalanis\kw_modules\ModulesLists\Record;
use kalanis\kw_storage\Access as storage_access;
use kalanis\kw_storage\Storage\Target\Memory;
use kalanis\kw_storage\StorageException;


class FileTest extends CommonTestClass
{
    /**
     * @throws StorageException
     * @throws ModuleException
     */
    public function testListing(): void
    {
        $lib = $this->getLib();
        $list = $lib->listing();
        $this->assertNotEmpty($list);

        usort($list, [$this, 'sortListing']);

        /** @var Record $entry */
        $entry = reset($list);
        $this->assertEquals('Dashboard', $entry->getModuleName());
        $this->assertTrue($entry->isEnabled());
        $this->assertEquals(['display' => 'no'], $entry->getParams());

        $entry = next($list);
        $this->assertEquals('Files', $entry->getModuleName());
        $this->assertTrue($entry->isEnabled());
        $this->assertEquals(['pos' => '4', 'image' => 'files/files.png'], $entry->getParams());

        $entry = next($list);
        $this->assertEquals('Images', $entry->getModuleName());
        $this->assertTrue($entry->isEnabled());
        $this->assertEquals(['pos' => '5', 'image' => 'images/images.png'], $entry->getParams());

        $entry = next($list);
        $this->assertEquals('Login', $entry->getModuleName());
        $this->assertFalse($entry->isEnabled());
        $this->assertEquals(['display' => 'no'], $entry->getParams());

        $entry = next($list);
        $this->assertEquals('Logout', $entry->getModuleName());
        $this->assertTrue($entry->isEnabled());
        $this->assertEquals(['menu' => 'system', 'pos' => '1', 'image' => 'system/logout.png'], $entry->getParams());

        $entry = next($list);
        $this->assertEquals('Menu', $entry->getModuleName());
        $this->assertFalse($entry->isEnabled());
        $this->assertEquals(['link' => 'menu/dashboard', 'pos' => '8', 'image' => 'menu.png'], $entry->getParams());

        $entry = next($list);
        $this->assertEquals('Personal', $entry->getModuleName());
        $this->assertTrue($entry->isEnabled());
        $this->assertEquals(['menu' => 'system', 'pos' => '3', 'image' => 'system/personal.png'], $entry->getParams());

        $entry = next($list);
        $this->assertEquals('Texts', $entry->getModuleName());
        $this->assertTrue($entry->isEnabled());
        $this->assertEquals(['pos' => '1', 'image' => 'texts/texts.png', 'name' => 'Texty'], $entry->getParams());

        $entry = next($list);
        $this->assertFalse($entry);
    }

    public function sortListing(Record $a, Record $b): int
    {
        return $a->getModuleName() <=> $b->getModuleName();
    }

    /**
     * @throws ModuleException
     * @throws StorageException
     */
    public function testProcess(): void
    {
        $lib = $this->getLib();

        $entry = $lib->get('Pedigree');
        $this->assertEmpty($entry);

        $this->assertTrue($lib->add('Pedigree', true, ['pos' => '8', 'image' => 'pedigree.png']));

        $entry = $lib->get('Pedigree');
        $this->assertNotEmpty($entry);
        $this->assertEquals('Pedigree', $entry->getModuleName());
        $this->assertTrue($entry->isEnabled());
        $this->assertEquals(['pos' => '8', 'image' => 'pedigree.png'], $entry->getParams());

        $entry->setEnabled(false);
        $this->assertTrue($lib->updateObject($entry));
        $entry = $lib->get('Pedigree');
        $this->assertNotEmpty($entry);
        $this->assertFalse($entry->isEnabled());

        $this->assertTrue($lib->updateBasic('Pedigree', null, ['pos' => '10']));
        $entry = $lib->get('Pedigree');
        $this->assertNotEmpty($entry);
        $this->assertEquals(['pos' => '10'], $entry->getParams());

        $this->assertTrue($lib->updateBasic('Pedigree', true, null));
        $entry = $lib->get('Pedigree');
        $this->assertNotEmpty($entry);
        $this->assertTrue($entry->isEnabled());

        $this->assertTrue($lib->remove('Pedigree'));
    }

    /**
     * @throws ModuleException
     * @throws StorageException
     */
    public function testProcessNone(): void
    {
        $lib = $this->getLib();
        $this->assertFalse($lib->add('Logout', false));
        $this->assertFalse($lib->updateBasic('Logout', null, null));
        $this->assertFalse($lib->updateBasic('This one does not exists', true, []));
        $unknown = new Record();
        $unknown->setModuleName('This one does not exists');
        $this->assertFalse($lib->updateObject($unknown));
    }

    /**
     * @throws StorageException
     * @return File
     */
    protected function getLib(): File
    {
        $storage = new Memory();
        $storage->save('prefix' . DIRECTORY_SEPARATOR . 'modules.777.conf',
            'Dashboard|1|display=no|' . "\r\n"
            . 'Login|0|display=no|' . "\r\n"
            . 'Logout|1|menu=system&pos=1&image=system/logout.png|' . "\r\n"
            . 'Personal|1|menu=system&pos=3&image=system/personal.png|' . "\r\n"
            . 'Texts|1|pos=1&image=texts/texts.png&name=Texty|' . "\r\n"
            . 'Files|1|pos=4&image=files/files.png|' . "\r\n"
            . 'Images|1|pos=5&image=images/images.png|' . "\r\n"
            . 'Menu|0|link=menu/dashboard&pos=8&image=menu.png|' . "\r\n"
            . '');
        $file = new File(new File\Storage((new storage_access\Factory())->getStorage($storage), 'prefix'), new ParamsFormat\Json());
        $file->setModuleLevel(777);
        return $file;
    }
}
