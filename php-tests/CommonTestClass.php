<?php

use kalanis\kw_storage\Interfaces\IStorage as IKwStorage;
use PHPUnit\Framework\TestCase;


/**
 * Class CommonTestClass
 * The structure for mocking and configuration seems so complicated, but it's necessary to let it be totally idiot-proof
 */
class CommonTestClass extends TestCase
{
}


class XStorage implements IKwStorage
{
    protected $dummy = [];

    public function check(string $key): bool
    {
        return true;
    }

    public function exists(string $key): bool
    {
        return isset($this->dummy[$key]);
    }

    public function load(string $key)
    {
        return $this->dummy[$key] ?? null ;
    }

    public function save(string $key, $data, ?int $timeout = null): bool
    {
        $this->dummy[$key] = $data;
        return true;
    }

    public function remove(string $key): bool
    {
        if ($this->exists($key)) {
            unset($this->dummy[$key]);
        }
        return true;
    }

    public function lookup(string $key): \Traversable
    {
        yield from [];
    }

    public function increment(string $key): bool
    {
        return false;
    }

    public function decrement(string $key): bool
    {
        return false;
    }

    public function removeMulti(array $keys): array
    {
        return [];
    }
}
