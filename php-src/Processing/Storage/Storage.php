<?php

namespace kalanis\kw_modules\Processing\Storage;


use kalanis\kw_modules\Interfaces\Processing\IStorage;
use kalanis\kw_modules\ModuleException;
use kalanis\kw_paths\Interfaces\IPaths;
use kalanis\kw_paths\Stuff;
use kalanis\kw_storage\Interfaces\IStorage as IKwStorage;
use kalanis\kw_storage\StorageException;


/**
 * Class Storage
 * @package kalanis\kw_modules\Processing\Storage
 */
class Storage implements IStorage
{
    /** @var IKwStorage */
    protected $storage = null;
    /** @var string */
    protected $moduleConfPath = '';
    /** @var string */
    protected $path = '';

    public function __construct(IKwStorage $storage, string $moduleConfPath)
    {
        $this->moduleConfPath = $moduleConfPath;
        $this->storage = $storage;
    }

    public function setModuleLevel(int $level): void
    {
        $this->path = Stuff::sanitize(implode(DIRECTORY_SEPARATOR, [
            $this->moduleConfPath,
            sprintf('%s.%d.%s', IPaths::DIR_MODULE, $level, IPaths::DIR_CONF )
        ]));
    }

    public function load()
    {
        $this->checkPath();
        try {
            return $this->storage->load($this->path);
        } catch (StorageException $ex) {
            throw new ModuleException('Problem with storage load', 0, $ex);
        }
    }

    public function save($records): void
    {
        $this->checkPath();
        try {
            $this->storage->save($this->path, $records);
        } catch (StorageException $ex) {
            throw new ModuleException('Problem with storage save', 0, $ex);
        }
    }

    /**
     * @throws ModuleException
     */
    protected function checkPath(): void
    {
        if (empty($this->path)) {
            throw new ModuleException('Site part and then file name is not set!');
        }
    }
}
