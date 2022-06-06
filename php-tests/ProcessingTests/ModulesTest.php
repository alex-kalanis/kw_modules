<?php

namespace ProcessingTests;


use CommonTestClass;
use kalanis\kw_modules\ModuleException;
use kalanis\kw_modules\Processing\Format\ClearFile;
use kalanis\kw_modules\Processing\ModuleRecord;
use kalanis\kw_modules\Processing\Modules;
use kalanis\kw_modules\Processing\Storage\Storage;
use kalanis\kw_storage\StorageException;


class ModulesTest extends CommonTestClass
{
    /**
     * @throws ModuleException
     * @throws StorageException
     */
    public function testSimple(): void
    {
        // content to dummy storage
        $storage = new \XStorage();
        $path = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'dummy.conf';
        $storage->save('modules.50.conf', file_get_contents($path));
        $this->assertNotEmpty($storage->load('modules.50.conf'));

        // now the test
        $rec = new ModuleRecord();
        $store = new Storage($storage, '');
        $lib = new Modules(new ClearFile($rec), $store, $rec);
        $lib->setLevel(50);
        $this->assertEquals(['Dashboard', 'Login', 'Logout', 'Personal', 'Texts', 'Files', 'Images', 'Menu'], $lib->listing());

        $testText = $lib->readNormalized('PERSONAL');
        $this->assertEquals($testText, $lib->readDirect('Personal'));
        $this->assertEquals('Personal', $testText->getModuleName());

        $lib->createNormalized('TESTING1');
        $lib->createDirect('testing2');

        $this->assertEquals(['Dashboard', 'Login', 'Logout', 'Personal', 'Texts', 'Files', 'Images', 'Menu', 'Testing1', 'testing2'], $lib->listing());
        $lib->updateNormalized('TESTING1', 'yep-nope-nope-yep');
        $lib->updateDirect('testing2', 'nope-yep-nope-yep');

        $lib->deleteNormalized('TESTING1');
        $lib->deleteDirect('testing2');

        $this->assertEquals(['Dashboard', 'Login', 'Logout', 'Personal', 'Texts', 'Files', 'Images', 'Menu'], $lib->listing());

        $lib->setLevel(45);
        $this->assertEquals([], $lib->listing());
    }
}
