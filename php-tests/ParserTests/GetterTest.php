<?php

namespace ParserTests;


use CommonTestClass;
use kalanis\kw_modules\ModuleException;
use kalanis\kw_modules\Parser\GetModules;
use kalanis\kw_modules\Parser\Record;


class GetterTest extends CommonTestClass
{
    /**
     * @throws ModuleException
     */
    public function testNothing(): void
    {
        $lib = new GetModules();
        $lib->setContent('blablabla  blablabla');
        $modules = $lib->process()->getFoundModules();
        $this->assertEmpty($modules);
    }

    /**
     * @throws ModuleException
     */
    public function testSimple(): void
    {
        $lib = new GetModules();
        $lib->setContent('blablabla {MODULE/} blablabla {OTHER-DATA/} blablabla {ANOTHER--DATA/} blablabla');
        $modules = $lib->process()->getFoundModules();
        $this->assertNotEmpty($modules);

        $module = reset($modules);
        /** @var Record $module */
        $this->assertEquals('Module', $module->getModuleName());
        $this->assertEquals(['Module'], $module->getModulePath());
        $this->assertEquals([], $module->getParams());
        $this->assertEquals('{MODULE/}', $module->getToChange());

        $module = next($modules);
        /** @var Record $module */
        $this->assertEquals('OtherData', $module->getModuleName());
        $this->assertEquals(['OtherData'], $module->getModulePath());
        $this->assertEquals([], $module->getParams());
        $this->assertEquals('{OTHER-DATA/}', $module->getToChange());

        $module = next($modules);
        /** @var Record $module */
        $this->assertEquals('Another', $module->getModuleName());
        $this->assertEquals(['Another', 'Data'], $module->getModulePath());
        $this->assertEquals([], $module->getParams());
        $this->assertEquals('{ANOTHER--DATA/}', $module->getToChange());

        $this->assertEmpty(next($modules));
    }

    /**
     * @throws ModuleException
     */
    public function testWithParams(): void
    {
        $lib = new GetModules();
        $lib->setContent('blablabla{MODULE}foo=bar&abc=def&ghi=12.5{/MODULE}blablabla');
        $modules = $lib->process()->getFoundModules();
        $this->assertNotEmpty($modules);

        $module = reset($modules);
        /** @var Record $module */
        $this->assertEquals('Module', $module->getModuleName());
        $this->assertEquals(['foo' => 'bar', 'abc' => 'def', 'ghi' => '12.5'], $module->getParams());
        $this->assertEquals('{MODULE}foo=bar&abc=def&ghi=12.5{/MODULE}', $module->getToChange());

        $this->assertEmpty(next($modules));
    }

    /**
     * @throws ModuleException
     */
    public function testManyWithParams(): void
    {
        $lib = new GetModules();
        $lib->setContent('blablabla{MODULE}foo=bar&abc=def&ghi=12.5{/MODULE}blablabla
        blablabla blablabla {OTHER-DATA}foo=def&abc=75{/OTHER-DATA} blablabla
        {ANOTHER--DATA}bar=def{/ANOTHER--DATA} blablabla');
        $modules = $lib->process()->getFoundModules();
        $this->assertNotEmpty($modules);

        $module = reset($modules);
        /** @var Record $module */
        $this->assertEquals('Module', $module->getModuleName());
        $this->assertEquals(['foo' => 'bar', 'abc' => 'def', 'ghi' => '12.5'], $module->getParams());
        $this->assertEquals('{MODULE}foo=bar&abc=def&ghi=12.5{/MODULE}', $module->getToChange());

        $module = next($modules);
        /** @var Record $module */
        $this->assertEquals('OtherData', $module->getModuleName());
        $this->assertEquals(['OtherData'], $module->getModulePath());
        $this->assertEquals(['foo' => 'def', 'abc' => '75'], $module->getParams());
        $this->assertEquals('{OTHER-DATA}foo=def&abc=75{/OTHER-DATA}', $module->getToChange());

        $module = next($modules);
        /** @var Record $module */
        $this->assertEquals('Another', $module->getModuleName());
        $this->assertEquals(['Another', 'Data'], $module->getModulePath());
        $this->assertEquals(['bar' => 'def'], $module->getParams());
        $this->assertEquals('{ANOTHER--DATA}bar=def{/ANOTHER--DATA}', $module->getToChange());

        $this->assertEmpty(next($modules));
    }

    /**
     * @throws ModuleException
     * Duplicate will be returned only once
     */
    public function testDuplicateWithParams(): void
    {
        $lib = new GetModules();
        $lib->setContent('blablabla{MODULE}foo=bar&abc=def&ghi=12.5{/MODULE}blablabla
        blablabla {MODULE}foo=bar&abc=def&ghi=12.5{/MODULE} blablabla blablabla');
        $modules = $lib->process()->getFoundModules();
        $this->assertNotEmpty($modules);

        $module = reset($modules);
        /** @var Record $module */
        $this->assertEquals('Module', $module->getModuleName());
        $this->assertEquals(['foo' => 'bar', 'abc' => 'def', 'ghi' => '12.5'], $module->getParams());
        $this->assertEquals('{MODULE}foo=bar&abc=def&ghi=12.5{/MODULE}', $module->getToChange());

        $this->assertEmpty(next($modules));
    }

//    /**
//     * @throws ModuleException
//     * In future this will be correct
//     * But I must solve problem with returning values first - it may return just simple value or the array of params
//     */
//    public function testOpeningTwoModules(): void
//    {
//        $lib = new GetModules();
//        $lib->setContent('blablabla{MODULE}foo=bar&baz={OTHER}ghi=12.5{/OTHER}{/MODULE} blablabla blablabla');
//        $this->expectException(ModuleException::class);
//        $lib->process();
//    }

    /**
     * @throws ModuleException
     */
    public function testEndingBeforeOpening(): void
    {
        $lib = new GetModules();
        $lib->setContent('blablabla{MODULE}foo=bar{/MODULE}blablabla
        blablabla {OTHER}ghi=12.5{/MODULE} {/OTHER} blablabla blablabla');
        $this->expectException(ModuleException::class);
        $lib->process();
    }

    /**
     * @throws ModuleException
     */
    public function testEndingFirst(): void
    {
        $lib = new GetModules();
        $lib->setContent('blablabla{/MODULE}foo=bar{/MODULE}blablabla blablabla');
        $this->expectException(ModuleException::class);
        $lib->process();
    }
}
