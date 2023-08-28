<?php

namespace kalanis\kw_modules\Interfaces\Modules;


/**
 * Class IHasTitle
 * @package kalanis\kw_modules\Interfaces\Modules
 * Module has title
 */
interface IHasTitle extends IModule
{
    /**
     * Return bar title
     * @return string
     */
    public function getTitle(): string;
}
