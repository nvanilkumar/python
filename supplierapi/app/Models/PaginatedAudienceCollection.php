<?php

namespace App\Models;

class PaginatedAudienceCollection implements \JsonSerializable
{
    private $paginationInfo;
    private $collection;

    public function __construct(PaginationInfo $paginationInfo, AudienceCollection $collection)
    {
        $this->paginationInfo = $paginationInfo;
        $this->collection = $collection;
    }

    public function getPaginationInfo() : PaginationInfo
    {
        return $this->paginationInfo;
    }

    public function getAudiences() : AudienceCollection
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