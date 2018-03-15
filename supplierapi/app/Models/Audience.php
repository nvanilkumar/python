<?php

namespace App\Models;

class Audience implements \JsonSerializable
{
    private $id;
    private $cxNumber;
    private $isRegistered;
    private $name;
    private $country;
    private $language;
    private $anticipatedIR;
    private $anticipatedLOI;
    private $actualIR;
    private $actualLOI;
    private $completesRemaining;
    private $expectedEndDate;
    private $category;
    private $subCategory;
    private $trueSample;
    private $verity;
    private $pii;
    private $segment;
    private $contractedCPI;
    private $status;

    public function getId() : int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getCxNumber() : string
    {
        return $this->cxNumber;
    }

    public function setCxNumber(string $number)
    {
        $this->cxNumber = $number;
    }

    public function getIsRegistered() : int
    {
        return $this->isRegistered;
    }

    public function setIsRegistered(int $value)
    {
        $this->isRegistered = $value;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name = null)
    {
        $this->name = $name;
    }

    public function getCountry() : string
    {
        return $this->country;
    }

    public function setCountry(string $country = null)
    {
        $this->country = $country;
    }

    public function getLanguage() : string
    {
        return $this->language;
    }

    public function setLanguage(string $language = null)
    {
        $this->language = $language;
    }

    public function getAnticipatedIR() : int
    {
        return $this->anticipatedIR;
    }

    public function setAnticipatedIR(int $ir = null)
    {
        $this->anticipatedIR = $ir;
    }

    public function getAnticipatedLOI() : int
    {
        return $this->anticipatedLOI;
    }

    public function setAnticipatedLOI(int $loi = null)
    {
        $this->anticipatedLOI = $loi;
    }

    public function getActualIR() : int
    {
        return $this->actualIR;
    }

    public function setActualIR(int $ir = null)
    {
        $this->actualIR = $ir;
    }

    public function getActualLOI() : int
    {
        return $this->actualLOI;
    }

    public function setActualLOI(int $loi = null)
    {
        $this->actualLOI = $loi;
    }

    public function getCompletesRemaining() : int
    {
        return $this->completesRemaining;
    }

    public function setCompletesRemaining(int $remaining)
    {
        $this->completesRemaining = $remaining;
    }

    public function getExpectedEndDate() : string
    {
        return $this->expectedEndDate;
    }

    public function setExpectedEndDate(string $date = null)
    {
        $this->expectedEndDate = $date;
    }

    public function getCategory() : string
    {
        return $this->category;
    }

    public function setCategory(string $category = null)
    {
        $this->category = $category;
    }

    public function getSubCategory() : string
    {
        return $this->subCategory;
    }

    public function setSubCategory(string $subCategory = null)
    {
        $this->subCategory = $subCategory;
    }

    public function getTrueSample() : int
    {
        return $this->trueSample;
    }

    public function setTrueSample(int $trueSample)
    {
        $this->trueSample = $trueSample;
    }

    public function getVerity() : int
    {
        return $this->verity;
    }

    public function setVerity(int $verity)
    {
        $this->verity = $verity;
    }

    public function getPii() : int
    {
        return $this->pii;
    }

    public function setPii(int $pii)
    {
        $this->pii = $pii;
    }

    public function getSegment() : string
    {
        return $this->segment;
    }

    public function setSegment(string $segment = null)
    {
        $this->segment = $segment;
    }

    public function getContractedCPI() : float
    {
        return $this->contractedCPI;
    }

    public function setContractedCPI(float $cpi)
    {
        $this->contractedCPI = $cpi;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function jsonSerialize() : \stdClass
    {
        $object = new \stdClass();
        $object->id = $this->id;
        $object->cxNumber = $this->cxNumber;
        $object->isRegistered = $this->isRegistered > 0 ? true : false;
        $object->name = $this->name;
        $object->country = $this->country;
        $object->language = $this->language;
        $object->anticipatedIR = $this->anticipatedIR;
        $object->anticipatedLOI = $this->anticipatedLOI;
        $object->actualIR = $this->actualIR;
        $object->actualLOI = $this->actualLOI;
        $object->completesRemaining = $this->completesRemaining;
        $endDate = new \DateTime($this->expectedEndDate, new \DateTimeZone('America/New_York'));
        $object->expectedEndDate = $endDate->format('Y-m-d\TH:i:s');
        $object->category = $this->category;
        $object->subCategory = $this->subCategory;
        $object->trueSample = $this->trueSample > 0 ? true : false;
        $object->verity = $this->verity > 0 ? true : false;
        $object->pii = $this->pii > 0 ? true : false;
        $object->segment = $this->segment;
        $object->contractedCPI = floatval($this->contractedCPI);
        $object->status = $this->status;

        return $object;
    }
}