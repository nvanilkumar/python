<?php

namespace App\Models;

class PaginationInfo implements \JsonSerializable
{
    private $totalItems;
    private $totalPages;
    private $page;

    public function __construct(int $totalItems, int $totalPages, int $page)
    {
        $this->totalItems = $totalItems;
        $this->totalPages = $totalPages;
        $this->page = $page;
    }

    public function getTotalItems() : int
    {
        return $this->totalItems;
    }

    public function getTotalPages() : int
    {
        return $this->totalPages;
    }

    public function getPage() : int
    {
        return $this->page;
    }

    public function jsonSerialize()
    {
        $object = new \stdClass();
        $object->totalItems = $this->totalItems;
        $object->totalPages = $this->totalPages;
        $object->page = $this->page;

        return $object;
    }
}