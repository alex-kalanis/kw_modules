<?php

namespace ProcessingTests;


use CommonTestClass;
use kalanis\kw_modules\Processing\ModuleRecord;


class RecordTest extends CommonTestClass
{
    public function testSimple(): void
    {
        $lib = new ModuleRecord();
        $this->assertEmpty($lib->getModuleName());
        $this->assertEmpty($lib->getParams());
        $lib->setModuleName('foo');
        $lib->updateParams('bar=baz');
        $this->assertEquals('foo', $lib->getModuleName());
        $this->assertEquals('bar=baz', $lib->getParams());
    }
}
