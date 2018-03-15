<?php

namespace App\Models;

class SurveyAccess implements \JsonSerializable
{
    private $liveLink;
    private $testLink;

    public function __construct(string $liveLink, string $testLink)
    {
        $this->liveLink = $liveLink;
        $this->testLink = $testLink;
    }

    public function getLiveLink() : string
    {
        return $this->liveLink;
    }

    public function getTestLink() : string
    {
        return $this->testLink;
    }

    public function jsonSerialize() : \stdClass
    {
        $object = new \stdClass();
        $object->live = $this->liveLink;
        $object->test = $this->testLink;

        return $object;
    }
}
