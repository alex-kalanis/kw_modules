<?php

namespace kalanis\kw_modules\Access;


use kalanis\kw_files\Interfaces\IProcessFiles;
use kalanis\kw_mapper\MapperException;
use kalanis\kw_modules\Interfaces\ILoader;
use kalanis\kw_modules\Interfaces\Lists\IModulesList;
use kalanis\kw_modules\ModulesLists\File;
use kalanis\kw_modules\ModulesLists\ParamsFormat;
use kalanis\kw_modules\Loaders;
use kalanis\kw_modules\Mixer\Processor;
use kalanis\kw_storage\Interfaces\IStorage;


/**
 * Class Factory
 * @package kalanis\kw_modules\Access
 * Factory to get instances to run
 */
class Factory
{
    /**
     * @param mixed $params
     * @throws MapperException
     * @return Processor
     */
    public function getProcessor($params): Processor
    {
        return new Processor($this->getLoader($params), $this->getSourceList($params));
    }

    /**
     * @param mixed $params
     * @throws MapperException
     * @return ILoader
     */
    public function getLoader($params): ILoader
    {
        if (is_object($params)) {
            if ($params instanceof ILoader) {
                return $params;
            }
        }
        if (is_array($params)) {
            if (isset($params['modules_loaders'])) {
                return $this->getLoader($params['modules_loaders']);
            }
            $what = [];
            foreach (array_values($params) as $item) {
                try {
                    $what[] = $this->getLoader($item);
                } catch (MapperException $ex) {
                    // not found - pass
                }
            }
            return new Loaders\ClassLoader($what);
        }
        if (is_string($params)) {
            try {
                /** @var class-string $params */
                $ref = new \ReflectionClass($params);
                $class = $ref->newInstanceArgs();
                if ($class && $class instanceof ILoader) {
                    return $class;
                }
            } catch (\ReflectionException $ex) {
                // nothing
            }
            switch ($params) {
                case 'admin':
                    return new Loaders\KwAdminLoader();
                case 'api':
                    return new Loaders\KwApiLoader();
                case 'web':
                    return new Loaders\KwLoader();
            }
        }
        throw new MapperException('No loader set!');
    }

    /**
     * @param mixed $params
     * @throws MapperException
     * @return IModulesList
     */
    public function getSourceList($params): IModulesList
    {
        if (is_object($params)) {
            if ($params instanceof IModulesList) {
                return $params;
            }
            if ($params instanceof IStorage) {
                return new File(new File\Storage($params, ''), new ParamsFormat\Http());
            }
            if ($params instanceof IProcessFiles) {
                return new File(new File\Files($params, []), new ParamsFormat\Http());
            }
        }
        if (is_array($params)) {
            if (isset($params['modules_source'])) {
                return $this->getSourceList($params['modules_source']);
            }
        }
        if (is_string($params)) {
            try {
                /** @var class-string $params */
                $ref = new \ReflectionClass($params);
                $class = $ref->newInstance();
                if ($class && $class instanceof IModulesList) {
                    return $class;
                }
            } catch (\ReflectionException $ex) {
                // nothing
            }
        }

        throw new MapperException('No source set!');
    }
}
