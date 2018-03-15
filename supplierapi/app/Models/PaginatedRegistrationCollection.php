<?php

namespace App\Models;

class PaginatedRegistrationCollection implements \JsonSerializable
{
    private $paginationInfo;
    private $collection;

    public function __construct(PaginationInfo $paginationInfo, RegistrationCollection $collection)
    {
        $this->paginationInfo = $paginationInfo;
        $this->collection = $collection;
    }

    public function getPaginationInfo() : PaginationInfo
    {
        return $this->paginationInfo;
    }

    public function getRegistrations() : RegistrationCollection
    {
        return $this->collection;
    }

    public function jsonSerialize()
    {
        $object = new \stdClass();
        $object->totalItems = $this->paginationInfo->getTotalItems();
        $object->totalPages = $this->paginationInfo->getTotalPages();
        $object->page = $this->paginationInfo->getPage();
        $object->data = $this->collection;

        return $object;
    }
}