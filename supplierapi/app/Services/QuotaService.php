<?php

namespace App\Services;

use App\Models\Condition;
use App\Models\ConditionCollection;
use App\Models\Qualifier;
use App\Models\QualifierCollection;
use App\Models\Quota;
use App\Models\QuotaCollection;
use App\Models\QuotaGroup;
use App\Models\QuotaGroupCollection;
use Illuminate\Database\DatabaseManager;

class QuotaService {
    private $db;

    const AUDIENCE_QUERY = 0;
    const AUDIENCE_SOURCE_QUERY = 1;

    /**
     * QuotaService constructor.
     * @param DatabaseManager $db
     */
    public function __construct(DatabaseManager $db)
    {
        $this->db = $db->connection();
    }

    /**
     * @param int $audienceId
     * @return QuotaGroupCollection
     */
    public function getAudienceQuotas(int $audienceId): QuotaGroupCollection
    {
        $demoQuotaGroups = $this->getDemoQuotaGroups(self::AUDIENCE_QUERY, $audienceId);
        $quotaGroups = $this->getQuotaGroups(self::AUDIENCE_QUERY, $audienceId);
        return $demoQuotaGroups->concat($quotaGroups);
    }

    /**
     * @param int $audienceSourceId
     * @return QuotaGroupCollection
     */
    public function getAudienceSourceQuotas(int $audienceSourceId): QuotaGroupCollection
    {
        $demoQuotaGroups = $this->getDemoQuotaGroups(self::AUDIENCE_SOURCE_QUERY, $audienceSourceId);
        $quotaGroups = $this->getQuotaGroups(self::AUDIENCE_SOURCE_QUERY, $audienceSourceId);
        return $demoQuotaGroups->concat($quotaGroups);
    }

    /**
     * @param int $queryType
     * @param int $id
     * @return QuotaGroupCollection
     * @throws \Exception
     */
    private function getDemoQuotaGroups(int $queryType, int $id)
    {
        $sql = "
            select distinct
              asq.dim_id group_id,
              dim.description group_description
            from
              ppl_assigned_quotas asq,
              ppl_demo_items dim,
              ppl_demo_item_lists dil
            where
              dim.id = dil.dim_id
              and dim.id = asq.dim_id
              and dil.id = asq.dil_id
              and asq.deleted_yn = 'N'
        ";

        if ($queryType == self::AUDIENCE_QUERY) {
            $sql .= " and asq.aud_id = :specifier";
        } else if ($queryType == self::AUDIENCE_SOURCE_QUERY) {
            $sql .= " and asq.aus_id = :specifier";
        } else {
            throw new \InvalidArgumentException('Invalid query type');
        }
        $queryParameters = ['specifier' => $id];

        $quotaGroups = new QuotaGroupCollection();

        $rows = $this->db->select($sql, $queryParameters);

        foreach ($rows as $row) {
            $quotas = $this->getDemoQuotaGroupQuotas($queryType, $id, $row->group_id);

            $quotas->removeWhere(function (Quota $quota) {
                return $quota->getQualifiers()->count() < 1;
            });
            $quotaGroup = new QuotaGroup($row->group_description, $quotas);

            $quotaGroups->add($quotaGroup);
        }

        $quotaGroups->removeWhere(function (QuotaGroup $quotaGroup) {
            return $quotaGroup->getQuotas()->count() < 1;
        });

        return $quotaGroups;
    }

    /**
     * @param int $queryType
     * @param int $id
     * @param int $quotaGroupId
     * @return QuotaCollection
     * @throws \Exception
     */
    private function getDemoQuotaGroupQuotas(int $queryType, int $id, int $quotaGroupId): QuotaCollection
    {
        if ($queryType == self::AUDIENCE_QUERY) {
            $sql = "
                    select
                        asq.id quota_id,
                        dil.id quota_dil_id,
                        asq.number_requested,
                        asq.number_filled,
                        (asq.number_exceedable - asq.number_filled) number_remaining
                    from
                        ppl_assigned_quotas asq
                        join ppl_demo_items dim on asq.dim_id = dim.id
                        join ppl_demo_item_lists dil on asq.dil_id = dil.id
                    where
                        dim.id = :quotaGroupId
                        and asq.deleted_yn = 'N'
                        and asq.aud_id = :specifier
            ";
        } else if ($queryType == self::AUDIENCE_SOURCE_QUERY) {
            $sql = "
                    select source_quotas.quota_id
                        , source_quotas.quota_dil_id
                        , least(source_quotas.number_requested, audience_quotas.number_requested) number_requested
                        , greatest(0, least(source_quotas.number_requested, audience_quotas.number_requested) - least(source_quotas.number_remaining, audience_quotas.number_remaining)) number_filled
                        , least(source_quotas.number_remaining, audience_quotas.number_remaining) number_remaining
                    from 
                    (
                        select
                            asq.id quota_id,
                            dil.id quota_dil_id,
                            asq.number_requested,
                            asq.number_filled,
                            (asq.number_exceedable - asq.number_filled) number_remaining
                        from
                            ppl_assigned_quotas asq
                            join ppl_demo_items dim on asq.dim_id = dim.id
                            join ppl_demo_item_lists dil on asq.dil_id = dil.id
                        where
                            dim.id = :quotaGroupId
                            and asq.deleted_yn = 'N'
                            and asq.aus_id = :specifier
                    ) source_quotas
                    join 
                    (
                        select
                            asq.id quota_id,
                            dil.id quota_dil_id,
                            asq.number_requested,
                            asq.number_filled,
                            (asq.number_exceedable - asq.number_filled) number_remaining
                        from
                            ppl_assigned_quotas asq
                            join ppl_demo_items dim on asq.dim_id = dim.id
                            join ppl_demo_item_lists dil on asq.dil_id = dil.id
                            join ppl_audience_sources aus on aus.aud_id = asq.aud_id
                        where
                            dim.id = :quotaGroupId
                            and asq.deleted_yn = 'N'
                            and aus.id = :specifier
                    ) audience_quotas on source_quotas.quota_dil_id = audience_quotas.quota_dil_id
                ";
        } else {
            throw new \Exception('Invalid query type');
        }
        $queryParameters = ['specifier' => $id, 'quotaGroupId' => $quotaGroupId];

        $quotas = new QuotaCollection();

        $rows = $this->db->select($sql, $queryParameters);

        foreach ($rows as $row) {
            $qualifiers = $this->getDilQualifiers($row->quota_dil_id);
            $quota = new Quota($row->quota_id, 'dil-' . $row->quota_dil_id, $row->number_requested, $row->number_filled, $row->number_remaining, $qualifiers);
            $quotas->add($quota);
        }

        $quotas->removeWhere(function (Quota $quota) {
            return $quota->getQualifiers()->count() < 1;
        });

        return $quotas;
    }

    /**
     * @param int $quotaId
     * @return QualifierCollection
     */
    private function getDilQualifiers(int $quotaId): QualifierCollection
    {
        $sql = "
            select
              dim.id qualifier_id,
              dim.name qualifier_name,
              dim.description qualifier_description,
              dil.id condition_id,
              dil.name condition_name,
              dil.description condition_description
            from
              ppl_demo_item_lists dil
              join ppl_demo_items dim on dil.dim_id = dim.id
              join ppl_demo_groups dmg on dim.dmg_id = dmg.id
            where
              dil.id = :quotaId
              and dmg.name = 'Standard US Demos'
        ";

        $queryParameters = ['quotaId' => $quotaId];

        $qualifiers = new QualifierCollection();

        $rows = $this->db->select($sql, $queryParameters);

        foreach ($rows as $row) {
            $condition = new Condition($row->condition_id, $row->condition_name, $row->condition_description);

            $qualifier = new Qualifier(
                $row->qualifier_id,
                $row->qualifier_name,
                $row->qualifier_description,
                new ConditionCollection([$condition])
            );

            $qualifiers->add($qualifier);
        }

        return $qualifiers;
    }

    /**
     * @param $queryType
     * @param int $id
     * @return QuotaGroupCollection
     * @throws \Exception
     */
    private function getQuotaGroups($queryType, int $id): QuotaGroupCollection
    {
        $sql = "
            select distinct
              qtg.id group_id,
              qtg.description group_description
            from 
              ppl_quota_groups qtg
              join ppl_assigned_quotas asq on asq.qtg_id = qtg.id
            where
              asq.deleted_yn = 'N'
        ";

        if ($queryType == self::AUDIENCE_QUERY) {
            $sql .= " and asq.aud_id = :specifier";
        } else if ($queryType == self::AUDIENCE_SOURCE_QUERY) {
            $sql .= " and asq.aus_id = :specifier";
        } else {
            throw new \Exception('Invalid query type');
        }
        $queryParameters = ['specifier' => $id];

        $quotaGroups = new QuotaGroupCollection();

        $rows = $this->db->select($sql, $queryParameters);

        foreach ($rows as $row) {
            $quotas = $this->getQuotaGroupQuotas($queryType, $id, $row->group_id);
            $quotaGroup = new QuotaGroup($row->group_description, $quotas);
            $quotaGroups->add($quotaGroup);
        }

        $quotaGroups->removeWhere(function (QuotaGroup $quotaGroup) {
            return $quotaGroup->getQuotas()->count() < 1;
        });

        return $quotaGroups;
    }

    /**
     * @param int $queryType
     * @param int $id
     * @param int $quotaGroupId
     * @return QuotaCollection
     * @throws \Exception
     */
    private function getQuotaGroupQuotas(int $queryType, int $id, int $quotaGroupId): QuotaCollection
    {
        if ($queryType == self::AUDIENCE_QUERY) {
            $sql = "
                    select
                        asq.id quota_id,
                        qti.id quota_item_id,
                        asq.number_requested,
                        asq.number_filled,
                        (asq.number_exceedable - asq.number_filled) number_remaining
                    from
                        ppl_quota_items qti
                        join ppl_quota_groups qtg on qtg.id = qti.qtg_id
                        join ppl_assigned_quotas asq on asq.qti_id = qti.id
                    where
                        asq.deleted_yn = 'N'
                        and qtg.id = :quotaGroupId
                        and asq.aud_id = :specifier
            ";
        } else if ($queryType == self::AUDIENCE_SOURCE_QUERY) {
            $sql = "
                    select source_quotas.quota_id
                        , source_quotas.quota_item_id
                        , least(source_quotas.number_requested, audience_quotas.number_requested) number_requested
                        , greatest(0, least(source_quotas.number_requested, audience_quotas.number_requested) - least(source_quotas.number_remaining, audience_quotas.number_remaining)) number_filled
                        , least(source_quotas.number_remaining, audience_quotas.number_remaining) number_remaining
                    from 
                    (
                        select
                            asq.id quota_id,
                            qti.id quota_item_id,
                            asq.number_requested,
                            asq.number_filled,
                            (asq.number_exceedable - asq.number_filled) number_remaining
                        from
                            ppl_quota_items qti
                            join ppl_quota_groups qtg on qtg.id = qti.qtg_id
                            join ppl_assigned_quotas asq on asq.qti_id = qti.id
                        where
                            asq.deleted_yn = 'N'
                            and qtg.id = :quotaGroupId
                            and asq.aus_id = :specifier
                    ) source_quotas
                    join 
                    (
                        select
                            asq.id quota_id,
                            qti.id quota_item_id,
                            asq.number_requested,
                            asq.number_filled,
                            (asq.number_exceedable - asq.number_filled) number_remaining
                        from
                            ppl_quota_items qti
                            join ppl_quota_groups qtg on qtg.id = qti.qtg_id
                            join ppl_assigned_quotas asq on asq.qti_id = qti.id
                            join ppl_audience_sources aus on aus.aud_id = asq.aud_id
                        where
                            asq.deleted_yn = 'N'
                            and qtg.id = :quotaGroupId
                            and aus.id = :specifier
                    ) audience_quotas on source_quotas.quota_item_id = audience_quotas.quota_item_id
            ";
        } else {
            throw new \Exception('Invalid query type');
        }
        $queryParameters = ['specifier' => $id, 'quotaGroupId' => $quotaGroupId];

        $quotas = new QuotaCollection();

        $rows = $this->db->select($sql, $queryParameters);

        foreach ($rows as $row) {
            $qualifiers = $this->getQtiQualifiers($row->quota_item_id);
            $quota = new Quota($row->quota_id, 'qti-' . $row->quota_item_id, $row->number_requested, $row->number_filled, $row->number_remaining, $qualifiers);
            $quotas->add($quota);
        }

        $quotas->removeWhere(function (Quota $quota) {
            return $quota->getQualifiers()->count() < 1;
        });

        return $quotas;
    }

    /**
     * @param int $quotaItemId
     * @return QualifierCollection
     */
    private function getQtiQualifiers(int $quotaItemId): QualifierCollection
    {
        $sql = "
            select distinct
              qtg.name group_name,
              qtg.description group_description,
              dim.id qualifier_id,
              dim.name qualifier_name,
              dim.description qualifier_description,
              qti.id condition_id,
              qti.name condition_name,
              qti.description condition_description
            from 
              ppl_quota_items qti
              join ppl_quota_groups qtg on qtg.id = qti.qtg_id
              join ppl_quota_item_lists qil on qil.qti_id = qti.id
              join ppl_demo_items dim on dim.id = qil.dim_id
              join ppl_demo_groups dmg on dim.dmg_id = dmg.id
            where
              qti.id = :quotaId
        ";
        $queryParameters = ['quotaId' => $quotaItemId];

        $rows = $this->db->select("select dim.name from ppl_demo_items dim join ppl_demo_groups dmg on dmg.id = dim.dmg_id where dmg.name = 'Standard US Demos'");

        $standardDemos = [];
        $bucketGroups = [
            'Standard Region 4',
            'DMAs',
            'State from Zips'
        ];

        foreach ($rows as $row) {
            $standardDemos[] = $row->name;
        }

        $qualifiers = new QualifierCollection();

        $rows = $this->db->select($sql, $queryParameters);

        foreach ($rows as $row) {
            $qualifier = null;

            if (in_array($row->group_name, $bucketGroups)) {
                $qualifier = new Qualifier(
                    $row->qualifier_id,
                    $row->group_name,
                    $row->group_description
                );
                $condition = new Condition($row->condition_id, $row->condition_name, $row->condition_description);
                $qualifier->setConditions(new ConditionCollection([$condition]));
            } elseif (in_array($row->qualifier_name, $standardDemos)) {
                $qualifier = new Qualifier(
                    $row->qualifier_id,
                    $row->qualifier_name,
                    $row->qualifier_description
                );
                $conditions = $this->getQtiQualifierConditions($quotaItemId, $row->qualifier_id);
                $qualifier->setConditions($conditions);
            } else {
                return new QualifierCollection();
            }

            $qualifiers->add($qualifier);
        }

        return $qualifiers;
    }

    private function getQtiQualifierConditions(int $quotaId, int $qualifierId): ConditionCollection
    {
        $sql = "
            select
              dil.id,
              dil.name,
              dil.description
            from
              ppl_quota_items qti
              join ppl_quota_groups qtg on qtg.id = qti.qtg_id
              join ppl_quota_item_lists qil on qil.qti_id = qti.id
              join ppl_demo_item_lists dil on qil.dil_id = dil.id
              join ppl_demo_items dim on dim.id = dil.dim_id
            where
              qti.id = :quotaId
              and dim.id = :qualifierId
        ";
        $queryParameters = ['quotaId' => $quotaId, 'qualifierId' => $qualifierId];

        $conditions = new ConditionCollection();

        $rows = $this->db->select($sql, $queryParameters);

        foreach ($rows as $row) {
            $condition = new Condition($row->id, $row->name, $row->description);
            $conditions->add($condition);
        }

        return $conditions;
    }
}