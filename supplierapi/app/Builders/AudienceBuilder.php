<?php

namespace App\Builders;

use App\Models\Audience;

class AudienceBuilder
{
    public function build(array $properties) : Audience
    {

        if (!array_key_exists('id', $properties)) {
            throw new \Exception("missing id property");
        }

        if (!array_key_exists('cxnumber', $properties)) {
            throw new \Exception('missing cxNumber property');
        }

        if (!array_key_exists('isregistered', $properties)) {
            throw new \Exception('missing isRegistered property');
        }

        if (!array_key_exists('name', $properties)) {
            throw new \Exception('missing name property');
        }

        if (!array_key_exists('country', $properties)) {
            throw new \Exception('missing country property');
        }

        if (!array_key_exists('language', $properties)) {
            throw new \Exception('missing language property');
        }

        if (!array_key_exists('anticipatedir', $properties)) {
            throw new \Exception('missing anticipateIR property');
        }

        if (!array_key_exists('anticipatedloi', $properties)) {
            throw new \Exception('missing anticipatedLOI property');
        }

        if (!array_key_exists('actualir', $properties)) {
            throw new \Exception('missing actualIR property');
        }

        if (!array_key_exists('actualloi', $properties)) {
            throw new \Exception('missing actualLOI property');
        }

        if (!array_key_exists('completesremaining', $properties)) {
            throw new \Exception('missing completesRemaining property');
        }

        if (!array_key_exists('expectedenddate', $properties)) {
            throw new \Exception('missing expectedEndDate property');
        }

        if (!array_key_exists('category', $properties)) {
            throw new \Exception('missing category property');
        }

        if (!array_key_exists('subcategory', $properties)) {
            throw new \Exception('missing subCategory property');
        }

        if (!array_key_exists('truesample', $properties)) {
            throw new \Exception('missing trueSample property');
        }

        if (!array_key_exists('verity', $properties)) {
            throw new \Exception('missing verity property');
        }

        if (!array_key_exists('pii', $properties)) {
            throw new \Exception('missing pii property');
        }

        if (!array_key_exists('segment', $properties)) {
            throw new \Exception('missing segment property');
        }

        if (!array_key_exists('contractedcpi', $properties)) {
            throw new \Exception('missing contractedCPI property');
        }

        $audience = new Audience();

        $audience->setId(intval($properties['id']));
        $audience->setCxNumber($properties['cxnumber']);
        $audience->setIsRegistered(intval($properties['isregistered']));
        $audience->setName($properties['name']);
        $audience->setCountry($properties['country']);
        $audience->setLanguage($properties['language']);
        $audience->setAnticipatedIR(intval($properties['anticipatedir']));
        $audience->setAnticipatedLOI(intval($properties['anticipatedloi']));
        $audience->setActualIR(intval($properties['actualir']));
        $audience->setActualLOI(intval($properties['actualloi']));
        $audience->setCompletesRemaining(intval($properties['completesremaining']));
        $audience->setExpectedEndDate($properties['expectedenddate']);
        $audience->setCategory($properties['category']);
        $audience->setSubCategory($properties['subcategory']);
        $audience->setTrueSample(intval($properties['truesample']));
        $audience->setVerity(intval($properties['verity']));
        $audience->setPii(intval($properties['pii']));
        $audience->setSegment($properties['segment']);
        $audience->setContractedCPI(floatval($properties['contractedcpi']));
        $audience->setStatus($properties['status']);

        return $audience;
    }
}