<?php

namespace kalanis\kw_modules\Interfaces\Processing;


use kalanis\kw_modules\ModuleException;


/**
 * Class IStorage
 * @package kalanis\kw_modules\Interfaces\Processing
 * Where in the system will be the information saved
 */
interface IStorage
{
    /**
     * Set which level will be called
     * @param int $level
     */
    public function setModuleLevel(int $level): void;

    /**
     * @return mixed formatted data
     * @throws ModuleException
     */
    public function load();

    /**
     * @param mixed $records formatted data
     * @throws ModuleException
     */
    public function save($records): void;
}
