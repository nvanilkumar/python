<?php

namespace App\Entities;

class Qualifier
{
    public $description;
    public $name;
    public $condition;
    
    public function __construct(string $description = "", string $name = "", QualifierCondition $condition)
    {
        $this->description = $description;
        $this->name = $name;
        $this->condition = $condition;
    }
}