<?php

namespace App\Models;

class QualifierCollection extends Collection
{
    public function add(Qualifier $qualifier)
    {
        $this->data[] = $qualifier;
    }

    public function remove(Qualifier $qualifier) : bool
    {
        $found = false;
        $count = count($this->data);

        for ($i = 0; $i < $count; ++$i) {

            if ($this->data[$i] == $qualifier) {
                unset($this->data[$i]);
                $found = true;
            }
        }
        $this->data = array_values($this->data);

        return $found;
    }
}