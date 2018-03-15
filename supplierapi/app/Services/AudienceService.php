<?php

namespace App\Services;

use App\Exceptions\OutOfBoundsException;
use App\Filters\ContextMap;
use App\Filters\SqlFilterResolver;
use App\Models\AudienceCollection;
use App\Models\PaginatedAudienceCollection;
use App\Models\PaginationInfo;
use Illuminate\Database\DatabaseManager;
use App\Builders\AudienceBuilder;

class AudienceService {
    private $db;
    private $audienceQuery = "
        select
          aud.id id
        , prj.ar_number cxnumber
        , case when aus.id is not null then 1 else 0 end isRegistered
        , aud.name name
        , dil.iso_value country
        , lcl.language_iso language
        , aud.anticipated_ir anticipatedIR
        , aud.anticipated_loi anticipatedLOI
        , aud.actual_incidence actualIR
        , aud.actual_loi actualLOI
        , (aud.requested_completes - aud.tot_completes) completesRemaining
        , to_char(aud.end_date, 'yyyy-mm-dd\"T\"hh24:mi:ss') expectedEndDate
        , act.name category
        , sub.name subCategory
        , case aud.truesample_level_code when 'N' then 0 else 1 end trueSample
        , case vlv.code when '0' then 0 else 1 end verity
        , case
            when vlv.code <> '0' then 1
            when aud.truesample_level_code <> 'N' then 1
            when aud.collect_address_yn <> 'N' then 1
            else 0
          end pii
        , ads.name segment
        , round((vnd.revenue_share_pct / 100 * aud.cpi),2) contractedCPI
        , case when aud.requested_completes <= aud.tot_completes then 'CLOSED'
              when fst.name != 'Open Fielding' then 'CLOSED'
              when aus.status is null then aud.auto_supply_status
              when aus.status = 'A' then 'OPEN'
              else 'CLOSED'
         end status
        from ppl_projects prj
        join ppl_field_stages fst on fst.id = prj.fst_id and fst.stage = 'O'
        join ppl_project_audiences aud on prj.id = aud.prj_id
        join ppl_locales lcl on lcl.id = aud.lcl_id
        join ppl_demo_item_lists dil on dil.id = aud.country_dil_id
        join ppl_audience_segments ads on ads.id = aud.ads_id
        join ppl_audience_categories act on act.id = aud.act_id
        left outer join ppl_audience_subcategories sub on sub.id = aud.asc_id
        join ppl_verity_levels vlv on vlv.id = aud.vlv_id
        join ppl_vendors vnd on vnd.id = :vendorId
        left outer join ppl_audience_sources aus on aus.aud_id = aud.id and aus.cam_id = vnd.id
    ";


    public function __construct(DatabaseManager $db)
    {
        $this->db = $db->connection();
    }

    public function exists(int $audienceId) : bool
    {
        $sql = "select id from ppl_project_audiences where id = :audienceId";
        $rows = null;

        $rows = $this->db->select($sql, ['audienceId' => $audienceId]);

        return count($rows) > 0;
    }

    public function isRegisterable(int $audienceId): bool
    {
        $sql = "
            select
                 aud.id id
            from ppl_project_audiences aud
            where aud.auto_supply_yn = 'Y'
            and aud.ain_id is null
            and aud.id = :audienceId
        ";
        $rows = null;

        $rows = $this->db->select($sql, ['audienceId' => $audienceId]);

        return count($rows) > 0;
    }

    public function getAudience(int $vendorId, int $audienceId)
    {
        $sql = $this->audienceQuery . ' where aud.id = :audienceId and (aud.auto_supply_yn = \'Y\' or aus.id is not null)';
        $values = ['vendorId' => $vendorId, 'audienceId' => $audienceId];

        $data = $this->db->select($sql, $values);

        if (count($data) < 1) {
            return null;
        }

        $builder = new AudienceBuilder();

        return $builder->build((array)$data[0]);
    }
    
    public function query(int $vendorId, array $filters, int $limit, int $page = 1) : PaginatedAudienceCollection
    {
        $sql = $this->audienceQuery . ' where aud.auto_supply_yn = \'Y\'';

        $resolver = new SqlFilterResolver([
            new ContextMap('id', 'aud.id'),
            new ContextMap('cxNumber', 'prj.ar_number'),
            new ContextMap('isRegistered', '(case when aus.id is not null then 1 else 0 end)', function ($value) {
                return filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            }),
            new ContextMap('name', 'aud.name'),
            new ContextMap('country', 'dil.iso_value'),
            new ContextMap('language', 'lcl.language_iso'),
            new ContextMap('anticipatedIR', 'aud.anticipated_ir'),
            new ContextMap('anticipatedLOI', 'aud.anticipated_loi'),
            new ContextMap('actualIR', 'aud.actual_incidence'),
            new ContextMap('actualLOI', 'aud.actual_loi'),
            new ContextMap('completesRemaining', '(aud.requested_completes - aud.tot_completes)'),
            new ContextMap('expectedEndDate', 'to_char(aud.end_date, \'yyyy-mm-dd"T"hh24:mi:ss\')', function ($value) {
                return (new \DateTime($value))->format('Y-m-d\TH:i:s');
            }),
            new ContextMap('category', 'act.name'),
            new ContextMap('subCategory', 'sub.name'),
            new ContextMap('trueSample', '(case tsl.code when \'N\' then 0 else 1 end)', function ($value) {
                return filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            }),
            new ContextMap('verity', '(case vlv.code when \'0\' then 0 else 1 end verity)', function ($value) {
                return filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            }),
            new ContextMap(
                'pii',
                '(case 
                      when vlv.code <> \'0\' then 1
                      when aud.truesample_level_code <> \'N\' then 1
                      when aud.collect_address_yn <> \'N\' then 1
                      else 0
                  end)',
                function ($value) {
                    return filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
                }
            ),
            new ContextMap('segment', 'ads.name'),
            new ContextMap('contractedCPI', 'ceil(((vnd.revenue_share_pct / 100) * aud.cpi) * 100) / 100'),
            new ContextMap(
                'status',
                '(case 
                    when aud.requested_completes > aud.tot_completes
                        and nvl(aus.status, \'A\') = \'A\'
                        and fst.name = \'Open Fielding\'
                    then aud.auto_supply_status
                    else \'CLOSED\'
                end)'
            )
        ]);
        $queryParameters = ['vendorId' => $vendorId];

        foreach ($filters as $filter) {
            $sql .= ' and ';
            $statement = $resolver->resolve($filter);
            $sql .= $statement->getSql();
            $placeholder = $statement->getPlaceholder();

            if ($placeholder !== null) {
                $queryParameters[$placeholder->getName()] = $placeholder->getValue();
            }
        }

        $countSql = "
            select
              count(*) total
            from ({$sql})
        ";

        $rows = $this->db->select($countSql, $queryParameters);
        $totalItems = $rows[0]->total;

        $pagedSql = "
            select * from (
              select /*+ FIRST_ROWS(n) */ a.*, ROWNUM rownumber from ({$sql}) a
              where ROWNUM <= :top
            )
            where rownumber > :offset
        ";

        $limit = $limit < 1 ? 0 : $limit;

        $totalPages = $limit > 0 ? ceil($totalItems / $limit) : 1;
        $totalPages = $totalPages > 0 ? $totalPages : 1;

        if ($page < 1 || $page > $totalPages) {
            throw new OutOfBoundsException('Page does not exist');
        }

        $offset = ($page - 1) * $limit;

        $queryParameters['top'] = $offset + $limit;
        $queryParameters['offset'] = $offset;

        $rows = $this->db->select($pagedSql, $queryParameters);

        $builder = new AudienceBuilder();
        $collection = new AudienceCollection();

        foreach ($rows as $row) {
            $audience = $builder->build((array)$row);
            $collection->add($audience);
        }

        return new PaginatedAudienceCollection(new PaginationInfo($totalItems, $totalPages, $page), $collection);
    }
}