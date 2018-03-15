<?php

namespace App\Models;

class Qualifier implements \JsonSerializable
{
    private $id;
    private $name;
    private $description;
    private $conditions;

    /**
     * Qualifier constructor.
     * @param int $id
     * @param string $name
     * @param string $description
     * @param ConditionCollection $conditions
     */
    public function __construct(int $id, string $name, string $description, ConditionCollection $conditions = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;

        if (is_null($conditions)) {
            $this->conditions = new ConditionCollection();
        } else {
            $this->conditions = $conditions;
        }
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

    /**
     * @return ConditionCollection
     */
    public function getConditions(): ConditionCollection
    {
        return $this->conditions;
    }

    /**
     * @param ConditionCollection $conditions
     */
    public function setConditions(ConditionCollection $conditions)
    {
        $this->conditions = $conditions;
    }
    
    public function jsonSerialize(): \stdClass
    {
        $object = new \stdClass();
        $object->name = $this->name;
        $object->description = $this->description;
        $object->conditions = $this->conditions;

        return $object;
    }
}