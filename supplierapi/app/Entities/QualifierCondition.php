<?php

namespace App\Entities;

class QualifierCondition
{
    public $description;
    public $name;
    
    public function __construct(string $description = "", string $name = "") {
        $this->description = $description;
        $this->name = $name;
    }
}