<?php

namespace kalanis\kw_modules\Output;


/**
 * Class RawCallback
 * @package kalanis\kw_modules
 * Output callback into Raw data
 */
class RawCallback extends AOutput
{
    /** @var callable */
    protected $callback = null;

    /**
     * @param callable $callback
     * @return $this
     */
    public function setCallback($callback): self
    {
        $this->callback = $callback;
        return $this;
    }

    public function output(): string
    {
        return strval(call_user_func($this->callback));
    }
}
