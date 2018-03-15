<?php

namespace App\Models;

use App\Exceptions\InvalidArgumentException;
use App\Exceptions\OutOfBoundsException;

class Collection implements \IteratorAggregate, \Countable, \ArrayAccess, \JsonSerializable
{
    protected $data;

    public function __construct(array $subject = [])
    {
        $this->data = $subject;
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function getIterator(): \Iterator
    {
        return new SimpleIterator($this->data);
    }

    public function jsonSerialize()
    {
        return $this->data;
    }

    public function offsetExists($offset)
    {
        if (!is_numeric($offset)) {
            throw new InvalidArgumentException('Expected number, got ' . $offset);
        }

        return array_key_exists($offset, $this->data);
    }

    public function offsetGet($offset)
    {
        if (!is_numeric($offset)) {
            throw new InvalidArgumentException('Expected number, got ' . $offset);
        }

        if (!$this->offsetExists($offset)) {
            throw new OutOfBoundsException('Index is out of range');
        }

        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (!is_numeric($offset)) {
            throw new InvalidArgumentException('Expected number, got ' . $offset);
        }

        if (!$this->offsetExists($offset)) {
            throw new OutOfBoundsException('Index is out of range');
        }

        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if (!is_numeric($offset)) {
            throw new InvalidArgumentException('Expected number, got ' . $offset);
        }

        if ($offset < 0 || $offset >= count($this->data)) {
            throw new OutOfBoundsException('Index is out of range');
        }

        unset($this->data[$offset]);
        $this->data = array_values($this->data);
    }

    public function removeAt(int $index)
    {
        $this->offsetUnset($index);
    }

    public function removeWhere(callable $predicate) {

        for ($i = (count($this->data) - 1); $i > -1; $i--) {

            if ($predicate($this->data[$i])) {
                unset($this->data[$i]);
            }
        }
        $this->data = array_values($this->data);
    }

    public function toArray()
    {
        return $this->data;
    }
}