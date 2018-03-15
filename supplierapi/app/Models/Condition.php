<?php

namespace App\Models;

class Condition implements \JsonSerializable
{
    private $id;
    private $name;
    private $description;

    /**
     * Condition constructor.
     * @param int $id
     * @param string $name
     * @param string $description
     */
    public function __construct(int $id, string $name, string $description) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }
    
    public function jsonSerialize()
    {
        $object = new \stdClass();
        $object->name = $this->name;
        $object->description = $this->description;

        return $object;
    }
}