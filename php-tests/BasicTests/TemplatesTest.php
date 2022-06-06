<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_confs\Config;
use kalanis\kw_confs\Interfaces\IConf;
use kalanis\kw_modules\Templates\ATemplate;
use kalanis\kw_paths\Path;
use kalanis\kw_templates\TemplateException;


class TemplatesTest extends CommonTestClass
{
    public function testSimple(): void
    {
        $path = new Path();
        $path->setDocumentRoot(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data');
        Config::init($path);
        Config::loadClass(new XTmplConf());

        $lib = new XTemplate();
        $this->assertEquals('cell', $lib->getTemplateName());
        $this->assertEquals('<td>{CELL_CONTENT}</td>', $lib->reset()->render());
    }

    public function testNoTemplate(): void
    {
        $path = new Path();
        $path->setDocumentRoot(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data');
        Config::init($path);
        Config::loadClass(new XTmplConf());

        $this->expectException(TemplateException::class);
        new XNopeTemplate();
    }
}


class XTemplate extends ATemplate
{
    protected $templateName = 'cell';

    protected function fillInputs(): void
    {
        // nothing to do
    }
}


class XNopeTemplate extends ATemplate
{
    protected $templateName = 'intentionally not exists';

    protected function fillInputs(): void
    {
        // nothing to do
    }
}


class XTmplConf implements IConf
{
    public function getConfName(): string
    {
        return 'Core';
    }

    public function getSettings(): array
    {
        return [
            'page.default_style' => 'Templates',
        ];
    }
}
