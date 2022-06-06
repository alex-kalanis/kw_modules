<?php

namespace kalanis\kw_modules\Loaders;


use kalanis\kw_modules\Interfaces\ILoader;
use kalanis\kw_modules\Interfaces\IModule;


/**
 * Class MultiLoader
 * @package kalanis\kw_modules\Loaders
 * Loading from multiple styles
 */
class MultiLoader implements ILoader
{
    /** @var ILoader[] */
    protected $subLoaders = [];

    public static function init(): self
    {
        return new static();
    }

    public function addLoader(ILoader $loader): self
    {
        $name = get_class($loader);
        $this->subLoaders[$name] = $loader;
        return $this;
    }

    public function load(string $module, ?string $constructPath = null, array $constructParams = []): ?IModule
    {
        foreach ($this->subLoaders as $loader) {
            if ($module = $loader->load($module, $constructPath, $constructParams)) {
                return $module;
            }
        }
        return null;
    }
}
