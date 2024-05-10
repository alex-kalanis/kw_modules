<?php

namespace MixerTests\PassedParams;


use CommonTestClass;
use kalanis\kw_modules\Mixer;


class ParamTest extends CommonTestClass
{
    public function testQuery(): void
    {
        $lib = new Mixer\PassedParams\HttpQuery();
        $this->assertEquals(['foo' => 'bar', 'abc' => 'def', 'ghi' => '12.5'], $lib->change('foo=bar&abc=def&ghi=12.5'));
    }

    public function testQueryDeep(): void
    {
        $lib = new Mixer\PassedParams\HttpQuery();
        $this->assertEquals(['foo' => 'bar', 'abc' => 'def', 'amp;ghi' => '12.5'], $lib->change('foo=bar&amp;abc=def&amp;amp;ghi=12.5'));
    }

    public function testSingle(): void
    {
        $lib = new Mixer\PassedParams\SingleParam();
        $this->assertEquals(['foo=bar&abc=def&ghi=12.5'], $lib->change('foo=bar&abc=def&ghi=12.5'));
    }
}
