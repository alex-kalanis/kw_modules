<?php

namespace kalanis\kw_modules\Interfaces;


use kalanis\kw_modules\ModuleException;


/**
 * Class ILoader
 * @package kalanis\kw_modules\Interfaces
 * Load translation data from defined source
 */
interface ILoader
{
    /**
     * @param string $module which module it will be looked for
     * @param string|null $constructPath next parts in target
     * @param array $constructParams params passed into __construct, mainly DI
     * @return IModule|null The module or null when nothing found
     * @throws ModuleException when problem has arisen
     */
    public function load(string $module, ?string $constructPath = null, array $constructParams = []): ?IModule;
}
