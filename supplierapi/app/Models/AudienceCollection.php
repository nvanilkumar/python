<?php

namespace App\Models;

class AudienceCollection extends Collection
{
    public function add(Audience $audience)
    {
        $this->data[] = $audience;
    }

    public function remove(Audience $audience): bool
    {
        $found = false;
        $count = count($this->data);

        for ($i = 0; $i < $count; ++$i) {

            if ($this->data[$i] == $audience) {
                unset($this->data[$i]);
                $found = true;
            }
        }
        $this->data = array_values($this->data);

        return $found;
    }
}