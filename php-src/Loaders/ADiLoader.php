<?php

namespace kalanis\kw_modules\Loaders;


use kalanis\kw_modules\Interfaces\ILoader;
use kalanis\kw_modules\Interfaces\IModule;
use kalanis\kw_modules\ModuleException;
use Psr\Container\ContainerInterface;


/**
 * Class ADiLoader
 * @package kalanis\kw_modules\Loaders
 * Use Dependency Injection
 * @codeCoverageIgnore contains external autoloader
 *
 * Name is passed as first big and the rest little ( ucfirst(strtolower($x)) )
 * - lookup by curly braces
 */
abstract class ADiLoader implements ILoader
{
    /** @var ContainerInterface */
    protected $container = null;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function load(array $module, array $constructParams = []): ?IModule
    {
        $classPath = $this->getClassName($module);
        if ($this->container->has($classPath)) {
            $module = $this->container->get($classPath);
            if (!$module instanceof IModule) {
                throw new ModuleException(sprintf('Class *%s* is not instance of IModule - check interface or query', $classPath));
            }
            return $module;
        }
        return null;
    }

    /**
     * @param string[] $module
     * @throws ModuleException
     * @return string
     */
    abstract protected function getClassName(array $module): string;
}
