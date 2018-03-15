<?php

namespace App\Models;

class RegistrationCollection extends Collection
{
    public function add(Registration $registration)
    {
        $this->data[] = $registration;
    }

    public function remove(Registration $registration) : bool
    {
        $found = false;
        $count = count($this->data);

        for ($i = 0; $i < $count; ++$i) {

            if ($this->data[$i] == $registration) {
                unset($this->data[$i]);
                $found = true;
            }
        }
        $this->data = array_values($this->data);

        return $found;
    }
}