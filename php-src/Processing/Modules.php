<?php

namespace kalanis\kw_modules\Processing;


use kalanis\kw_modules\Interfaces\IModuleRecord;
use kalanis\kw_modules\Interfaces\Processing\IFormat;
use kalanis\kw_modules\Interfaces\Processing\IStorage;
use kalanis\kw_modules\ModuleException;


/**
 * Class Modules
 * @package kalanis\kw_modules
 * Processing over modules - CRUD
 */
class Modules
{
    /** @var IStorage */
    protected $storage = null;
    /** @var IFormat */
    protected $format = null;
    /** @var IModuleRecord */
    protected $baseRecord = null;
    /** @var IModuleRecord[] */
    protected $records = [];

    public function __construct(IFormat $format, IStorage $storage, IModuleRecord $baseRecord)
    {
        $this->format = $format;
        $this->storage = $storage;
        $this->baseRecord = $baseRecord;
    }

    public function setLevel(int $level): void
    {
        $this->records = [];
        $this->storage->setModuleLevel($level);
    }

    /**
     * @return string[]
     * @throws ModuleException
     */
    public function listing(): array
    {
        $this->loadOnDemand();
        return array_keys($this->records);
    }

    /**
     * @param string $moduleName
     * @throws ModuleException
     */
    public function createNormalized(string $moduleName): void
    {
        $this->add(Support::normalizeModuleName($moduleName));
        $this->save();
    }

    /**
     * @param string $moduleName
     * @throws ModuleException
     */
    public function createDirect(string $moduleName): void
    {
        $this->add($moduleName);
        $this->save();
    }

    /**
     * @param string $moduleName
     * @throws ModuleException
     */
    protected function add(string $moduleName): void
    {
        $this->loadOnDemand();
        if (!isset($this->records[$moduleName])) {
            $record = clone $this->baseRecord;
            $record->setModuleName($moduleName);
            $this->records[$moduleName] = $record;
        }
    }

    /**
     * @param string $moduleName
     * @return IModuleRecord|null
     * @throws ModuleException
     */
    public function readNormalized(string $moduleName): ?IModuleRecord
    {
        return $this->get(Support::normalizeModuleName($moduleName));
    }

    /**
     * @param string $moduleName
     * @return IModuleRecord|null
     * @throws ModuleException
     */
    public function readDirect(string $moduleName): ?IModuleRecord
    {
        return $this->get($moduleName);
    }

    /**
     * @param string $moduleName
     * @return IModuleRecord|null
     * @throws ModuleException
     */
    protected function get(string $moduleName): ?IModuleRecord
    {
        $this->loadOnDemand();
        return $this->records[$moduleName] ?: null ;
    }

    /**
     * @param string $moduleName
     * @param string $params
     * @throws ModuleException
     */
    public function updateNormalized(string $moduleName, string $params): void
    {
        $this->update(Support::normalizeModuleName($moduleName), $params);
        $this->save();
    }

    /**
     * @param string $moduleName
     * @param string $params
     * @throws ModuleException
     */
    public function updateDirect(string $moduleName, string $params): void
    {
        $this->update($moduleName, $params);
        $this->save();
    }

    /**
     * @param string $moduleName
     * @param string $params
     * @throws ModuleException
     */
    protected function update(string $moduleName, string $params): void
    {
        $this->loadOnDemand();
        if (isset($this->records[$moduleName])) {
            $this->records[$moduleName]->updateParams($params);
        }
    }

    /**
     * @param string $moduleName
     * @throws ModuleException
     */
    public function deleteNormalized(string $moduleName): void
    {
        $this->remove(Support::normalizeModuleName($moduleName));
        $this->save();
    }

    /**
     * @param string $moduleName
     * @throws ModuleException
     */
    public function deleteDirect(string $moduleName): void
    {
        $this->remove($moduleName);
        $this->save();
    }

    /**
     * @param string $moduleName
     * @throws ModuleException
     */
    protected function remove(string $moduleName): void
    {
        $this->loadOnDemand();
        if (isset($this->records[$moduleName])) {
            unset($this->records[$moduleName]);
        }
    }

    /**
     * @throws ModuleException
     */
    protected function loadOnDemand(): void
    {
        if (empty($this->records)) {
            $records = $this->load();
            $this->records = array_combine(array_map([$this, 'nameFromRecord'], $records), $records);
        }
    }

    public function nameFromRecord(IModuleRecord $record): string
    {
        return $record->getModuleName();
    }

    /**
     * @return IModuleRecord[]
     * @throws ModuleException
     */
    protected function load(): array
    {
        return $this->format->unpack($this->storage->load());
    }

    /**
     * @throws ModuleException
     */
    protected function save(): void
    {
        $this->storage->save($this->format->pack($this->records));
    }
}
