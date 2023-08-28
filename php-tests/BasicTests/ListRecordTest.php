<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_modules\ModulesLists\Record;


class ListRecordTest extends CommonTestClass
{
    public function testSimple(): void
    {
        $lib = new Record();
        $this->assertEmpty($lib->getModuleName());
        $this->assertEmpty($lib->getParams());
        $this->assertFalse($lib->isEnabled());
        $lib->setModuleName('test');
        $this->assertEquals('test', $lib->getModuleName());
        $lib->setParams(['tst' => 'foo']);
        $this->assertEquals(['tst' => 'foo'], $lib->getParams());
        $lib->setEnabled(true);
        $this->assertTrue($lib->isEnabled());
    }
}
