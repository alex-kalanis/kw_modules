<?php

namespace kalanis\kw_modules\Loaders;


use kalanis\kw_modules\Interfaces\ILoader;
use kalanis\kw_modules\Interfaces\IModule;
use kalanis\kw_modules\ModuleException;
use Psr\Container\ContainerInterface;


/**
 * Class KwDiLoader
 * @package kalanis\kw_modules
 * Load modules data from defined targets - use Dependency Injection
 * @codeCoverageIgnore contains external autoloader
 */
class DiLoader implements ILoader
{
    /** @var ContainerInterface */
    protected $container = null;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function load(string $module, ?string $constructPath = null, array $constructParams = []): ?IModule
    {
        $classPath = empty($constructPath) ? $module : sprintf('%s\%s', $module, $constructPath);
        if ($this->container->has($classPath)) {
            $module = $this->container->get($classPath);
            if (!$module instanceof IModule) {
                throw new ModuleException(sprintf('Class *%s* is not instance of IModule - check interface or query', $classPath));
            }
            return $module;
        }
        return null;
    }
}
