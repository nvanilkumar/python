<?php

namespace App\Models;

class ConditionCollection extends Collection
{
    public function add(Condition $condition)
    {
        $this->data[] = $condition;
    }

    public function remove(Condition $condition): bool
    {
        $found = false;
        $count = count($this->data);

        for ($i = 0; $i < $count; ++$i) {

            if ($this->data[$i] == $condition) {
                unset($this->data[$i]);
                $found = true;
            }
        }
        $this->data = array_values($this->data);

        return $found;
    }
}