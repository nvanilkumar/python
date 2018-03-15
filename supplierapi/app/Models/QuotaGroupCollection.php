<?php

namespace App\Models;

class QuotaGroupCollection extends Collection
{
    public function add(QuotaGroup $quotaGroup)
    {
        $this->data[] = $quotaGroup;
    }

    public function concat(QuotaGroupCollection $collection)
    {
        $current = $this->data;

        foreach ($collection as $item) {
            $current[] = $item;
        }

        return new self($current);
    }

    public function remove(QuotaGroup $quotaGroup) : bool
    {
        $found = false;
        $count = count($this->data);

        for ($i = 0; $i < $count; ++$i) {

            if ($this->data[$i] == $quotaGroup) {
                unset($this->data[$i]);
                $found = true;
            }
        }
        $this->data = array_values($this->data);

        return $found;
    }
}