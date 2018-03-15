<?php

namespace App\Entities;

class QuotaGroup
{
    public $description;
    public $quotas;
    
    public function __construct(string $description = "", array $quotas = array()) {
        $this->description = $description;
        $this->quotas = $quotas;
    }
}