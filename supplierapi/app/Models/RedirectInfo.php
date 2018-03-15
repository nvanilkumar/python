<?php

namespace App\Models;

class RedirectInfo
{
    private $complete;
    private $fail;
    private $fraud;
    private $quotaFull;
    private $terminate;
    private $alreadyTaken;

    public function __construct(string $complete, string $fail, string $fraud, string $quotaFull, string $terminate, string $alreadyTaken)
    {
        $this->complete = $complete;
        $this->fail = $fail;
        $this->fraud = $fraud;
        $this->quotaFull = $quotaFull;
        $this->terminate = $terminate;
        $this->alreadyTaken = $alreadyTaken;
    }

    public function getComplete() : string
    {
        return $this->complete;
    }

    public function getFail() : string
    {
        return $this->fail;
    }

    public function getFraud() : string
    {
        return $this->fraud;
    }

    public function getQuotaFull() : string
    {
        return $this->quotaFull;
    }

    public function getTerminate() : string
    {
        return $this->terminate;
    }

    public function getAlreadyTaken() : string
    {
        return $this->alreadyTaken;
    }
}