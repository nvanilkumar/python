<?php

namespace App\Models;

use App\Exceptions\OutOfBoundsException;

class SimpleIterator implements \Iterator
{
    private $subject;
    private $position = 0;

    public function __construct(array $subject)
    {
        $this->subject = $subject;
    }

    public function current()
    {
        if (!$this->valid()) {
            throw new OutOfBoundsException('Index out of range');
        }

        return $this->subject[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function valid()
    {
        return array_key_exists($this->position, $this->subject);
    }
}