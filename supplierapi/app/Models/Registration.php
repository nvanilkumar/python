<?php

namespace App\Models;

class Registration implements \JsonSerializable
{
    private $id;
    private $audienceId;
    private $vendorId;
    private $dateCreated;
    private $intent;
    private $contractedCpi;
    private $totalCompletes;
    private $surveyAccess;

    /**
     * Registration constructor.
     * @param int $id
     * @param int $audienceId
     * @param int $vendorId
     * @param \DateTime $dateCreated
     * @param int $intent
     * @param float $contractedCpi
     * @param int $totalCompletes
     * @param SurveyAccess $surveyAccess
     */
    public function __construct(
        int $id,
        int $audienceId,
        int $vendorId,
        \DateTime $dateCreated,
        int $intent,
        float $contractedCpi,
        int $totalCompletes,
        SurveyAccess $surveyAccess
    )
    {
        $this->id = $id;
        $this->audienceId = $audienceId;
        $this->vendorId = $vendorId;
        $this->dateCreated = $dateCreated;
        $this->intent = $intent;
        $this->contractedCpi = $contractedCpi;
        $this->totalCompletes = $totalCompletes;
        $this->surveyAccess = $surveyAccess;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getAudienceId(): int
    {
        return $this->audienceId;
    }

    /**
     * @param int $audienceId
     */
    public function setAudienceId(int $audienceId)
    {
        $this->audienceId = $audienceId;
    }

    /**
     * @return int
     */
    public function getVendorId(): int
    {
        return $this->vendorId;
    }

    /**
     * @param int $vendorId
     */
    public function setVendorId(int $vendorId)
    {
        $this->vendorId = $vendorId;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreated(): \DateTime
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTime $dateCreated
     */
    public function setDateCreated(\DateTime $dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return int
     */
    public function getIntent(): int
    {
        return $this->intent;
    }

    /**
     * @param int $intent
     */
    public function setIntent(int $intent)
    {
        $this->intent = $intent;
    }

    /**
     * @return float
     */
    public function getContractedCpi(): float
    {
        return $this->contractedCpi;
    }

    /**
     * @param float $contractedCpi
     */
    public function setContractedCpi(float $contractedCpi)
    {
        $this->contractedCpi = $contractedCpi;
    }

    /**
     * @return int
     */
    public function getTotalCompletes(): int
    {
        return $this->totalCompletes;
    }

    /**
     * @param int $totalCompletes
     */
    public function setTotalCompletes(int $totalCompletes)
    {
        $this->totalCompletes = $totalCompletes;
    }

    /**
     * @return SurveyAccess
     */
    public function getSurveyAccess(): SurveyAccess
    {
        return $this->surveyAccess;
    }

    /**
     * @param SurveyAccess $surveyAccess
     */
    public function setSurveyAccess(SurveyAccess $surveyAccess)
    {
        $this->surveyAccess = $surveyAccess;
    }

    public function jsonSerialize()
    {
        $object = new \stdClass();
        $object->id = $this->id;
        $object->audienceId = $this->audienceId;
        $object->dateCreated = $this->dateCreated->format('Y-m-d\TH:i:s');
        $object->intent = $this->intent;
        $object->contractedCPI = $this->contractedCpi;
        $object->totalCompletes = $this->totalCompletes;
        $object->entryLinks = $this->surveyAccess;

        return $object;
    }
}