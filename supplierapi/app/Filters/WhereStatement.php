<?php

namespace App\Filters;

class WhereStatement
{
    private $sql;
    private $placeholder;

    public function __construct(string $sql, Placeholder $placeholder = null)
    {
        $this->sql = $sql;
        $this->placeholder = $placeholder;
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return $this->sql;
    }

    /**
     * @return Placeholder|null
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }
}