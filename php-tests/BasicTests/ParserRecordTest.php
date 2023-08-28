<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_modules\Parser\Record;


class ParserRecordTest extends CommonTestClass
{
    public function testSimple(): void
    {
        $lib = new Record();
        $this->assertEmpty($lib->getModuleName());
        $this->assertEmpty($lib->getParams());
        $this->assertEmpty($lib->getModulePath());
        $this->assertEmpty($lib->getToChange());
        $this->assertEmpty($lib->getWillReplace());

        $lib->setModuleName('test');
        $this->assertEquals('test', $lib->getModuleName());
        $lib->setParams(['tst' => 'foo']);
        $this->assertEquals(['tst' => 'foo'], $lib->getParams());
        $lib->setModulePath(['foo', 'bar']);
        $this->assertEquals(['foo', 'bar'], $lib->getModulePath());

        $lib->setContentToChange('something');
        $this->assertEquals('something', $lib->getToChange());
        $lib->setWhatWillReplace('else');
        $this->assertEquals('else', $lib->getWillReplace());
    }
}
