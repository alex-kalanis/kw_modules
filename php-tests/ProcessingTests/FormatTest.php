<?php

namespace ProcessingTests;


use CommonTestClass;
use kalanis\kw_modules\Interfaces\IModuleRecord;
use kalanis\kw_modules\Processing\Format\ClearFile;
use kalanis\kw_modules\Processing\ModuleRecord;


class FormatTest extends CommonTestClass
{
    public function testClearFile(): void
    {
        $lib = new ClearFile(new ModuleRecord());
        $structure = $lib->unpack($lib->pack($this->getData()));

        $data = reset($structure);
        $this->assertEquals('foo', $data->getModuleName());
        $this->assertEquals('bar', $data->getParams());

        $data = next($structure);
        $this->assertEquals('abc', $data->getModuleName());
        $this->assertEquals('def', $data->getParams());

        $this->assertFalse(next($structure));
    }

    protected function getData(): array
    {
        return [
            $this->initData('foo', 'bar'),
            $this->initData('abc', 'def'),
        ];
    }

    protected function initData(string $name, string $params): IModuleRecord
    {
        $rec = new ModuleRecord();
        $rec->setModuleName($name);
        $rec->updateParams($params);
        return $rec;
    }
}
