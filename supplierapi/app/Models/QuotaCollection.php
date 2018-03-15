<?php

namespace App\Models;

class QuotaCollection extends Collection
{
    public function add(Quota $quota)
    {
        $this->data[] = $quota;
    }

    public function remove(Quota $quota) : bool
    {
        $found = false;
        $count = count($this->data);

        for ($i = 0; $i < $count; ++$i) {

            if ($this->data[$i] == $quota) {
                unset($this->data[$i]);
                $found = true;
            }
        }
        $this->data = array_values($this->data);

        return $found;
    }
}