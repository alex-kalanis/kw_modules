<?php

namespace kalanis\kw_modules;


use kalanis\kw_input\Interfaces\IVariables;
use kalanis\kw_modules\Interfaces\ILoader;
use kalanis\kw_modules\Interfaces\ISitePart;
use kalanis\kw_modules\Processing\Modules;
use kalanis\kw_modules\Processing\Support;
use kalanis\kw_templates\TemplateException;


/**
 * Class SubModules
 * @package kalanis\kw_modules
 * Parse source template to get submodules
 *
 * @todo: prehodit na pouhe tahani modulu z autoloaderu
 * @todo: vubec rozlozit - protoze je tu moc odpovednosti
 *     - vyziskane ocekavane moduly
 *     - parsovani dat ze zdroje
 *     - init onech modulu
 *     - flaknuti obsahu modulu zpatky do zdroje
 */
class SubModules
{
    /** @var ILoader */
    protected $loader = null;
    /** @var Modules */
    protected $moduleProcessor = null;

    public function __construct(ILoader $loader, Modules $processor)
    {
        $this->loader = $loader;
        $this->moduleProcessor = $processor;
    }

    /**
     * @param Templates\ATemplate $template
     * @param IVariables $inputs
     * @param int $level
     * @param array $sharedParams
     * @return Templates\ATemplate
     * @throws ModuleException
     */
    public function fill(Templates\ATemplate $template, IVariables $inputs, int $level, array $sharedParams = []): Templates\ATemplate
    {
        $this->moduleProcessor->setLevel($level);
        $modules = $this->moduleProcessor->listing();

        # map another modules and prepare to load them
        foreach ($modules as $module) {
            $templateName = Support::templateModuleName($module);
            $tagOpen = '{' . $templateName . '}'; # pos of modules parts
            $tagClose = '{/' . $templateName . '}';
            while (true) {
                try {
                    $beginFrom = $template->position($tagOpen);
                } catch (TemplateException $ex) {
                    // not found anymore
                    break;
                }
                $templateParams = [];
                $contentToChange = $tagOpen;
                try {
                    $endOn = $template->position($tagClose); # have {/DUMMY} or throw Exception
                    $beginLength = mb_strlen($tagOpen);
                    $paramsString = $template->getSubstring($beginFrom + $beginLength,$endOn - $beginFrom - $beginLength );
                    $contentToChange = $tagOpen . $paramsString . $tagClose;
                    if (!is_numeric(mb_strpos($paramsString,'{'))) { # pos between {DUMMY} and {/DUMMY} doesn't contain { (begining of another module)
                        # all ok...
                        $templateParams = Support::paramsIntoArray($paramsString); # get params for module
                    }
                } catch (TemplateException $ex) {
                    // do nothing
                }

                $moduleInfo = $this->moduleProcessor->readNormalized($module);
                $moduleInfo = $moduleInfo ?? $this->moduleProcessor->readDirect($module);
                if (!$moduleInfo) {
                    continue;
                }
                $moduleClass = $this->loader->load($moduleInfo->getModuleName());
                if (!$moduleClass) {
                    continue;
                }
                $moduleClass->init($inputs, array_merge(
                    $sharedParams,
                    Support::paramsIntoArray($moduleInfo->getParams()),
                    $templateParams,
                    [ISitePart::KEY_LEVEL => $level]
                ));
                $moduleClass->process();
                $template->change($contentToChange, $moduleClass->output()->output());
            }
        }
        return $template;
    }
}
