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
        $lib->setContent('blablabla {MODULE/} blablabla {OTHER_DATA/} blablabla {ANOTHER__DATA/} blablabla');
        $modules = $lib->process()->getFoundModules();
        $this->assertNotEmpty($modules);

        $module = reset($modules);
        /** @var Record $module */
        $this->assertEquals('Module', $module->getModuleName());
        $this->assertEquals(['Module'], $module->getModulePath());
        $this->assertEquals('', $module->getContent());
        $this->assertEquals('{MODULE/}', $module->getToChange());

        $module = next($modules);
        /** @var Record $module */
        $this->assertEquals('OtherData', $module->getModuleName());
        $this->assertEquals(['OtherData'], $module->getModulePath());
        $this->assertEquals('', $module->getContent());
        $this->assertEquals('{OTHER_DATA/}', $module->getToChange());

        $module = next($modules);
        /** @var Record $module */
        $this->assertEquals('Another', $module->getModuleName());
        $this->assertEquals(['Another', 'Data'], $module->getModulePath());
        $this->assertEquals('', $module->getContent());
        $this->assertEquals('{ANOTHER__DATA/}', $module->getToChange());

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
        $this->assertEquals('foo=bar&abc=def&ghi=12.5', $module->getContent());
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
        blablabla blablabla {OTHER_DATA}foo=def&abc=75{/OTHER_DATA} blablabla
        {ANOTHER__DATA}bar=def{/ANOTHER__DATA} blablabla {CHANGE-LEFT}{/CHANGE-LEFT}');
        $modules = $lib->process()->getFoundModules();
        $this->assertNotEmpty($modules);

        $module = reset($modules);
        /** @var Record $module */
        $this->assertEquals('Module', $module->getModuleName());
        $this->assertEquals('foo=bar&abc=def&ghi=12.5', $module->getContent());
        $this->assertEquals('{MODULE}foo=bar&abc=def&ghi=12.5{/MODULE}', $module->getToChange());

        $module = next($modules);
        /** @var Record $module */
        $this->assertEquals('OtherData', $module->getModuleName());
        $this->assertEquals(['OtherData'], $module->getModulePath());
        $this->assertEquals('foo=def&abc=75', $module->getContent());
        $this->assertEquals('{OTHER_DATA}foo=def&abc=75{/OTHER_DATA}', $module->getToChange());

        $module = next($modules);
        /** @var Record $module */
        $this->assertEquals('Another', $module->getModuleName());
        $this->assertEquals(['Another', 'Data'], $module->getModulePath());
        $this->assertEquals('bar=def', $module->getContent());
        $this->assertEquals('{ANOTHER__DATA}bar=def{/ANOTHER__DATA}', $module->getToChange());

        $module = next($modules);
        /** @var Record $module */
        $this->assertEquals('Change-left', $module->getModuleName());
        $this->assertEquals(['Change-left'], $module->getModulePath());
        $this->assertEquals('', $module->getContent());
        $this->assertEquals('{CHANGE-LEFT}{/CHANGE-LEFT}', $module->getToChange());

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
        $this->assertEquals('foo=bar&abc=def&ghi=12.5', $module->getContent());
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
