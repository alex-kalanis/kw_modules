<?php

namespace kalanis\kw_modules;


use kalanis\kw_input\Interfaces\IEntry;
use kalanis\kw_input\Interfaces\IFiltered;


/**
 * Class AModule
 * @package kalanis\kw_modules
 * Basic class for each module
 *
 * __construct() is for DI/class building
 * process() is for that hard work
 * output() is for getting output class/data
 */
abstract class AModule implements Interfaces\Modules\IModule
{
    /** @var IFiltered|null */
    protected $inputs = null;
    /** @var array<int|string, bool|float|int|string|array<int|string>> */
    protected $params = [];

    public function init(IFiltered $inputs, array $passedParams): void
    {
        $this->inputs = $inputs;
        $this->params = $passedParams;
    }

    public static function getClassName(string $class): string
    {
        $classParts = explode('\\', $class);
        return end($classParts);
    }

    protected function isJson(): bool
    {
        if ($this->inputs) {
            $json = $this->inputs->getInArray('json', [IEntry::SOURCE_CLI, IEntry::SOURCE_POST, IEntry::SOURCE_GET]);
            return !empty($json);
        }
        return false;
    }

    protected function isRaw(): bool
    {
        if ($this->inputs) {
            $raw = $this->inputs->getInArray('raw', [IEntry::SOURCE_CLI, IEntry::SOURCE_POST, IEntry::SOURCE_GET]);
            return !empty($raw);
        }
        return false;
    }

    /**
     * @param string $key
     * @param bool|float|int|string|array<int|string>|null $default
     * @return bool|float|int|string|array<int|string>|null
     */
    protected function getFromParam(string $key, $default = null)
    {
        return $this->params[$key] ?? $default ;
    }
}
