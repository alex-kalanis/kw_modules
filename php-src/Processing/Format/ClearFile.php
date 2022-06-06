<?php

namespace kalanis\kw_modules\Processing\Format;


use kalanis\kw_modules\Interfaces\Processing\IFormat;
use kalanis\kw_modules\Interfaces\IModuleRecord;


/**
 * Class ClearFile
 * @package kalanis\kw_modules\Processing\Format
 */
class ClearFile implements IFormat
{
    const PARAM_SEPARATOR = '|';
    const LINE_SEPARATOR = "\r\n";

    /** @var IModuleRecord */
    protected $baseRecord = null;

    public function __construct(IModuleRecord $baseRecord)
    {
        $this->baseRecord = $baseRecord;
    }

    /**
     * @param IModuleRecord[] $records
     * @return mixed
     */
    public function pack(array $records)
    {
        return implode(self::LINE_SEPARATOR, array_map([$this, 'toLine'], $records)) . self::LINE_SEPARATOR;
    }

    public function toLine(IModuleRecord $record): string
    {
        return implode(static::PARAM_SEPARATOR, [$record->getModuleName(), $record->getParams(), '']);
    }

    /**
     * @param mixed $content
     * @return IModuleRecord[]
     */
    public function unpack($content): array
    {
        return array_map([$this, 'fillRecord'],
            array_filter(explode(static::LINE_SEPARATOR, strval($content)), [$this, 'useLine'])
        );
    }

    public function useLine(string $line): bool
    {
        return !empty($line) && ('#' != $line[0]);
    }

    public function fillRecord(string $line): IModuleRecord
    {
        list($name, $params, ) = explode(static::PARAM_SEPARATOR, $line, 3);
        $record = clone $this->baseRecord;
        $record->setModuleName($name);
        $record->updateParams($params);
        return $record;
    }
}
