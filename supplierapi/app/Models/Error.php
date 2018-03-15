<?php

namespace App\Models;

class Error implements \JsonSerializable
{
    private $message;
    private $code;

    public function __construct(string $message, int $code = 0)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function getMessage() : string
    {
        return $this->message;
    }

    public function getCode() : int
    {
        return $this->code;
    }

    public function jsonSerialize() : \stdClass
    {
        $object = new \stdClass();
        $object->message = $this->message;
        $object->code = $this->code;

        return $object;
    }
}