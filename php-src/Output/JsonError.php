<?php

namespace kalanis\kw_modules\Output;


/**
 * Class Json
 * @package kalanis\kw_modules
 * Error Output into Json
 */
class JsonError extends AOutput
{
    /** @var array<string|int, string|int|float|array<string|int|float|array<string|int|float>>> */
    protected $content = null;

    /**
     * @param string|int $code
     * @param string $message
     * @return $this
     */
    public function setContent($code, string $message): self
    {
        $this->content = compact('code', 'message');
        return $this;
    }

    public function output(): string
    {
        if (!headers_sent()) {
            // @codeCoverageIgnoreStart
            header('Content-Type: application/json');
        }
        // @codeCoverageIgnoreEnd
        return strval(json_encode($this->content));
    }
}
