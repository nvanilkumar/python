<?php

namespace App\Models;

class Quota implements \JsonSerializable
{
    private $id;
    private $displayId;
    private $requested;
    private $filled;
    private $remaining;
    private $qualifiers;

    /**
     * Quota constructor.
     * @param int $id
     * @param string $displayId
     * @param int $requested
     * @param int $filled
     * @param int $remaining
     * @param QualifierCollection|null $qualifiers
     */
    public function __construct(int $id, string $displayId, int $requested, int $filled, int $remaining, QualifierCollection $qualifiers = null)
    {
        $this->id = $id;
        $this->displayId = $displayId;
        $this->requested = $requested;
        $this->filled = $filled;
        $this->remaining = $remaining;

        if ($qualifiers == null) {
            $this->qualifiers = new QualifierCollection();
        } else {
            $this->qualifiers = $qualifiers;
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getDisplayId(): string
    {
        return $this->displayId;
    }

    /**
     * @param string $displayId
     */
    public function setDisplayId(string $displayId)
    {
        $this->displayId = $displayId;
    }

    /**
     * @return int
     */
    public function getRequested(): int
    {
        return $this->requested;
    }

    /**
     * @param int $requested
     */
    public function setRequested(int $requested)
    {
        $this->requested = $requested;
    }

    /**
     * @return int
     */
    public function getFilled(): int
    {
        return $this->filled;
    }

    /**
     * @param int $filled
     */
    public function setFilled(int $filled)
    {
        $this->filled = $filled;
    }

    /**
     * @return int
     */
    public function getRemaining(): int
    {
        return $this->remaining;
    }

    /**
     * @param int $remaining
     */
    public function setRemaining(int $remaining)
    {
        $this->remaining = $remaining;
    }

    /**
     * @return QualifierCollection
     */
    public function getQualifiers(): QualifierCollection
    {
        return $this->qualifiers;
    }

    /**
     * @param QualifierCollection $qualifiers
     */
    public function setQualifiers(QualifierCollection $qualifiers)
    {
        $this->qualifiers = $qualifiers;
    }
    
    public function jsonSerialize()
    {
        $object = new \stdClass();
        $object->id = $this->displayId;
        $object->requested = $this->requested;
        $object->filled = $this->filled;
        $object->remaining = $this->remaining;
        $object->qualifiers = $this->qualifiers;

        return $object;
    }
}