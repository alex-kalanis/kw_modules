<?php

namespace kalanis\kw_modules\Output;


/**
 * Class Json
 * @package kalanis\kw_modules
 * Error Output into Json
 */
class JsonError extends AOutput
{
    protected $content = null;

    public function setContent($code, string $message)
    {
        $this->content = compact('code', 'message');
        return $this;
    }

    public function output(): string
    {
        if (!headers_sent()) {
            // @codeCoverageIgnoreStart
            header("Content-Type: application/json");
        }
        // @codeCoverageIgnoreEnd
        return json_encode($this->content);
    }
}
