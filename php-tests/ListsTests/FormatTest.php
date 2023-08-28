<?php

namespace ListsTests;


use CommonTestClass;
use kalanis\kw_modules\ModulesLists\ParamsFormat;


class FormatTest extends CommonTestClass
{
    /**
     * @param array $in
     * @dataProvider paramsProvider
     */
    public function testParamsJson(array $in): void
    {
        $lib = new ParamsFormat\Json();
        $this->assertEquals($in, $lib->unpack($lib->pack($in)));
    }

    /**
     * @param array $in
     * @dataProvider paramsProvider
     */
    public function testParamsSerial(array $in): void
    {
        $lib = new ParamsFormat\Serialize();
        $this->assertEquals($in, $lib->unpack($lib->pack($in)));
    }

    /**
     * @param array $in
     * @dataProvider paramsProvider
     */
    public function testParamsHttp(array $in): void
    {
        $lib = new ParamsFormat\Http();
        $this->assertEquals($in, $lib->unpack($lib->pack($in)));
    }

    public function paramsProvider(): array
    {
        return [
            [['foo' => 'bar']],
            [['foo' => 'bar', 'baz' => 'eab']],
        ];
    }
}
