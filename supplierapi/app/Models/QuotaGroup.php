<?php

namespace App\Models;

class QuotaGroup implements \JsonSerializable
{
    private $description;
    private $quotas;

    /**
     * QuotaGroup constructor.
     * @param string $description
     * @param QuotaCollection|null $quotas
     */
    public function __construct(string $description, QuotaCollection $quotas = null) {
        $this->description = $description;

        if ($quotas == null) {
            $this->quotas = new QuotaCollection();
        } else {
            $this->quotas = $quotas;
        }
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
     * @return QuotaCollection
     */
    public function getQuotas(): QuotaCollection
    {
        return $this->quotas;
    }

    /**
     * @param QuotaCollection $quotas
     */
    public function setQuotas(QuotaCollection $quotas)
    {
        $this->quotas = $quotas;
    }
    
    public function jsonSerialize()
    {
        $object = new \stdClass();
        $object->quotas = $this->quotas;    

        return $object;
    }
}