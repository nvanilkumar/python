<?php

namespace App\Services\Blendr;

class Error {
    private $message;
    private $code;

    public function __construct(string $message, string $code)
    {
        $this->message = $message;
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }


}