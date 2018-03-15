<?php

namespace App\Filters;

class Filter
{
    private $property;
    private $equality;
    private $value;

    /**
     * Filter constructor.
     * @param string $property
     * @param string $equality
     * @param string $value
     */
    public function __construct(string $property, string $equality, $value)
    {
        $this->property = $property;
        $this->equality = $equality;
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->property . ' ' . $this->equality . ' ' . $this->value;
    }

    /**
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @param string $property
     */
    public function setProperty(string $property)
    {
        $this->property = $property;
    }

    /**
     * @return string
     */
    public function getEquality(): string
    {
        return $this->equality;
    }

    /**
     * @param string $equality
     */
    public function setEquality(string $equality)
    {
        $this->equality = $equality;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     * @param string $filter
     * @return Filter
     */
    public static function parse(string $filter): Filter
    {
        $parts = explode(' ', $filter, 3);

        if (count($parts) < 3) {
            throw new \InvalidArgumentException('Unable to parse filter \'' . $filter . '\'. Invalid number of arguments. Got ' . count($parts));
        }

        $property = $parts[0];
        $equality = $parts[1];
        $value = $parts[2];

        if (!Equality::validate($equality)) {
            throw new \InvalidArgumentException('Invalid equality \'' . $equality . '\' provided in filter \'' . $filter . '\'');
        }

        return new self($property, $equality, $value);
    }
}