<?php

namespace App\Entities;

class QuotaStatus
{
    public $requested;
    public $filled;
    public $remaining;
    
    public function __construct(int $requested = 0, int $filled = 0, int $remaining = 0) {
        $this->requested = $requested;
        $this->filled = $filled;
        $this->remaining = $remaining;
    }
}