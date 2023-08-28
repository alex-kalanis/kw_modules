<?php

namespace kalanis\kw_modules\ModulesLists\File;


use kalanis\kw_files\FilesException;
use kalanis\kw_files\Interfaces\IProcessFiles;
use kalanis\kw_modules\Interfaces\Lists\IFile;
use kalanis\kw_modules\ModuleException;
use kalanis\kw_paths\Interfaces\IPaths;
use kalanis\kw_paths\PathsException;


/**
 * Class Files
 * @package kalanis\kw_modules\ModulesLists\File
 */
class Files implements IFile
{
    /** @var IProcessFiles */
    protected $files = null;
    /** @var string[] */
    protected $moduleConfPath = [];
    /** @var string[] */
    protected $path = [];

    /**
     * @param IProcessFiles $files
     * @param string[] $moduleConfPath
     */
    public function __construct(IProcessFiles $files, array $moduleConfPath)
    {
        $this->moduleConfPath = $moduleConfPath;
        $this->files = $files;
    }

    public function setModuleLevel(int $level): void
    {
        $this->path = array_merge($this->moduleConfPath, [
            sprintf('%s.%d.%s', IPaths::DIR_MODULE, $level, IPaths::DIR_CONF )
        ]);
    }

    public function load(): string
    {
        try {
            $data = $this->files->readFile($this->getPath());
            return is_resource($data) ? strval(stream_get_contents($data, -1, 0)) : strval($data);
        } catch (FilesException | PathsException $ex) {
            throw new ModuleException('Problem with storage load', 0, $ex);
        }
    }

    public function save(string $records): bool
    {
        try {
            return $this->files->saveFile($this->getPath(), $records);
        } catch (FilesException | PathsException $ex) {
            throw new ModuleException('Problem with storage save', 0, $ex);
        }
    }

    /**
     * @throws ModuleException
     * @return string[]
     */
    protected function getPath(): array
    {
        if (empty($this->path)) {
            throw new ModuleException('Path to config is not set!');
        }
        return $this->path;
    }
}
