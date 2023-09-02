<?php

namespace TraitsTests;


use CommonTestClass;
use kalanis\kw_modules\Interfaces\IMdTranslations;
use kalanis\kw_modules\Traits\TMdLang;
use kalanis\kw_modules\Translations;


class LangTest extends CommonTestClass
{
    public function testPass(): void
    {
        $lib = new XLang();
        $lib->setMdLang(new XTrans());
        $this->assertNotEmpty($lib->getMdLang());
        $this->assertInstanceOf(XTrans::class, $lib->getMdLang());
        $lib->setMdLang(null);
        $this->assertInstanceOf(Translations::class, $lib->getMdLang());
    }
}


class XLang
{
    use TMdLang;
}


class XTrans implements IMdTranslations
{
    public function mdNoLoaderSet(): string
    {
        return 'mock';
    }

    public function mdNoSourceSet(): string
    {
        return 'mock';
    }

    public function mdNotInstanceOfIModule(string $classPath): string
    {
        return 'mock';
    }

    public function mdNoModuleFound(): string
    {
        return 'mock';
    }

    public function mdConfPathNotSet(): string
    {
        return 'mock';
    }

    public function mdStorageTargetNotSet(): string
    {
        return 'mock';
    }

    public function mdStorageLoadProblem(): string
    {
        return 'mock';
    }

    public function mdStorageSaveProblem(): string
    {
        return 'mock';
    }

    public function mdNoOpeningTag(): string
    {
        return 'mock';
    }

    public function mdNoEndingTag(string $module): string
    {
        return 'mock';
    }
}
