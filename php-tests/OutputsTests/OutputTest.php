<?php

namespace OutputTests;


use CommonTestClass;
use kalanis\kw_modules\Output;


class OutputTest extends CommonTestClass
{
    public function testBasic(): void
    {
        $lib = new XOutput();
        $this->assertEquals('dummy output', (string) $lib);
        $this->assertFalse($lib->canWrap());
    }

    public function testRaw(): void
    {
        $lib = new Output\Raw();
        $lib->setContent('got everything');
        $this->assertEquals('got everything', (string) $lib);
        $this->assertFalse($lib->canWrap());
    }

    public function testRawCallback(): void
    {
        $lib = new Output\RawCallback();
        $lib->setCallback([$this, 'mockCallback']);
        $this->assertEquals('from callback', (string) $lib);
        $this->assertFalse($lib->canWrap());
    }

    public function testDumpCallback(): void
    {
        $lib = new Output\DumpingCallback();
        $lib->setCallback([$this, 'mockCallback']);
        $this->assertEquals('from callback', (string) $lib);
        $this->assertFalse($lib->canWrap());
    }

    public function testHtml(): void
    {
        $lib = new Output\Html();
        $lib->setContent('what you want');
        $this->assertEquals('what you want', (string) $lib);
        $this->assertTrue($lib->canWrap());
    }

    public function testJson(): void
    {
        $lib = new Output\Json();
        $lib->setContent(['get' => 'what you want']);
        $this->assertEquals('{"get":"what you want"}', (string) $lib);
        $this->assertFalse($lib->canWrap());
    }

    public function testJsonError(): void
    {
        $lib = new Output\JsonError();
        $lib->setContent(987, 'cannot happen');
        $this->assertEquals('{"code":987,"message":"cannot happen"}', (string) $lib);
        $this->assertFalse($lib->canWrap());
    }

    public function testJsonErrorStructure(): void
    {
        $lib = new Output\JsonError();
        $lib->setContentStructure(987, ['cannot happen', 'its impossible']);
        $this->assertEquals('{"code":987,"message":["cannot happen","its impossible"]}', (string) $lib);
        $this->assertFalse($lib->canWrap());
    }

    public function mockCallback(): string
    {
        return 'from callback';
    }
}


class XOutput extends Output\AOutput
{
    public function output(): string
    {
        return 'dummy output';
    }
}
