<?php

namespace App\Filters;

class ContextMap
{
    private $property;
    private $column;
    private $conversion;

    public function __construct(string $property, string $column, callable $conversion = null)
    {
        $this->property = $property;
        $this->column = $column;

        if ($conversion == null) {
            $this->conversion = function ($value) { return $value; };
        } else {
            $this->conversion = $conversion;
        }
    }

    /**
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @return string
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function convert($value)
    {
        return call_user_func($this->conversion, $value);
    }
}