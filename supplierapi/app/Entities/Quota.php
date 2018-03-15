<?php

namespace App\Entities;

class Quota
{
    public $qualifiers;
    public $status;
    
    public function __construct(array $qualifiers = array(), QuotaStatus $status)
    {
        $this->qualifiers = $qualifiers;
        $this->status = $status;
    }
}