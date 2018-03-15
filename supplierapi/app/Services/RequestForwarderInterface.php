<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;

interface RequestForwarderInterface
{
    public function forward(string $path): Response;
}