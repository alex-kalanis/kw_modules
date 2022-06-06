<?php

namespace kalanis\kw_modules\Interfaces\Processing;


use kalanis\kw_modules\Interfaces\IModuleRecord;


/**
 * Class IFormat
 * @package kalanis\kw_modules\Interfaces\Processing
 * How the information will be saved in system
 */
interface IFormat
{
    /**
     * @param IModuleRecord[] $records
     * @return mixed data to storage
     */
    public function pack(array $records);

    /**
     * @param mixed $content data from storage
     * @return IModuleRecord[]
     */
    public function unpack($content): array;
}
