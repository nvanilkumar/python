<?php

namespace App\Services;

use App\Exceptions\OutOfBoundsException;
use App\Filters\ContextMap;
use App\Filters\Equality;
use App\Filters\Filter;
use App\Filters\SqlFilterResolver;
use App\Models\PaginatedRegistrationCollection;
use App\Models\PaginationInfo;
use App\Models\RedirectInfo;
use App\Models\Registration;
use App\Models\RegistrationCollection;
use App\Models\SurveyAccess;
use Illuminate\Database\DatabaseManager;

class RegistrationService
{
    private $db;
    
    public function __construct(DatabaseManager $db)
    {
        $this->db = $db->connection();
    }
    
    public function exists(int $audienceId, int $vendorId)
    {
        $exists = false;
        $sql = 'select id from ppl_audience_sources where cam_id = :vendorId and aud_id = :audienceId';

        $results = $this->db->select($sql, [
            'audienceId' => $audienceId,
            'vendorId' => $vendorId
        ]);

        if (count($results) > 0) {
            $exists = true;
        }

        return $exists;
    }

    public function query(array $filters, int $limit, int $page = 1): PaginatedRegistrationCollection
    {
        $sql = "
            select
              aus.id id,
              aus.aud_id audienceId,
              aus.cam_id vendorId,
              to_char(aus.date_created, 'yyyy-mm-dd\"T\"hh24:mi:ss') dateCreated,
              aus.contracted_qty intent,
              round((vnd.revenue_share_pct / 100 * aud.cpi),2) contractedCPI,
              aus.tot_completes totalCompletes
            from
              ppl_audience_sources aus
              join ppl_vendors vnd on aus.cam_id = vnd.id
              join ppl_project_audiences aud on aus.aud_id = aud.id 
        ";

        $resolver = new SqlFilterResolver([
            new ContextMap('id', 'aus.id'),
            new ContextMap('audienceId', 'aus.aud_id'),
            new ContextMap('vendorId', 'aus.cam_id'),
            new ContextMap('dateCreated', 'to_char(aus.date_created, \'yyyy-mm-dd"T"hh24:mi:ss\')', function ($value) {
                return (new \DateTime($value))->format('Y-m-d\TH:i:s');
            }),
            new ContextMap('intent', 'aus.contracted_qty'),
            new ContextMap('contractedCPI', 'round((vnd.revenue_share_pct / 100 * aud.cpi),2)'),
            new ContextMap('totalCompletes', 'aus.tot_completes')
        ]);
        $count = 0;
        $queryParameters = [];

        foreach ($filters as $filter) {

            if ($count == 0) {
                $sql .= ' where ';
            } else {
                $sql .= ' and ';
            }
            $statement = $resolver->resolve($filter);
            $sql .= $statement->getSql();
            $placeholder = $statement->getPlaceholder();

            if ($placeholder !== null) {
                $queryParameters[$placeholder->getName()] = $placeholder->getValue();
            }
            $count++;
        }

        $sql .= " order by aus.date_created desc";

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

        $registrations = new RegistrationCollection();

        foreach ($rows as $row) {
            $id = intval($row->id);
            $audienceId = intval($row->audienceid);
            $vendorId = intval($row->vendorid);
            $dateCreated = new \DateTime($row->datecreated, new \DateTimeZone('America/New_York'));
            $intent = intval($row->intent);
            $contractedCpi = floatval($row->contractedcpi);
            $totalCompletes = intval($row->totalcompletes);
            $surveyAccess = $this->getLinks($vendorId, $id);

            $registration = new Registration($id, $audienceId, $vendorId, $dateCreated, $intent, $contractedCpi, $totalCompletes, $surveyAccess);
            $registrations->add($registration);
        }

        return new PaginatedRegistrationCollection(new PaginationInfo($totalItems, $totalPages, $page), $registrations);
    }
    
    public function register(int $audienceId, int $vendorId, int $intent, RedirectInfo $redirects, float $contractedCPI)
    {
        $audienceSourceId = null;

        try {
            $insertQuery = "insert into ppl_audience_sources
                            (
                              aud_id,
                              cam_id,
                              contracted_qty,
                              contracted_cpi,
                              status,
                              percent_requested,
                              api_service_yn
                            )
                            values
                            (
                              :audienceId,
                              :vendorId,
                              :intent,
                              :contractedCPI,
                              'A',
                              100,
                              'Y'
                            )
                            returning id into :audienceSourceId";
            
            $pdo = $this->db->getPdo();
            $stmt = $pdo->prepare($insertQuery);
            $stmt->bindValue(":audienceId", $audienceId);
            $stmt->bindValue(":vendorId", $vendorId);
            $stmt->bindValue(":intent", $intent);
            $stmt->bindValue(":contractedCPI", $contractedCPI);
            $stmt->bindParam(":audienceSourceId", $audienceSourceId, \PDO::PARAM_INT, 16);
            $stmt->execute();
        }
        catch (\Exception $ex) {
            throw new \Exception($ex->getMessage(), 10024, $ex);
        }

        //insert quotas for audience source
        try {
            $insertQuery = "insert into count.ppl_assigned_quotas
                            ( 
                              aus_id, 
                              dil_id, 
                              dim_id, 
                              qti_id, 
                              qtg_id, 
                              percent_requested, 
                              number_requested, 
                              exceed_quota, 
                              percent_exceedable, 
                              number_exceedable, 
                              display_order, 
                              dependant_yn, 
                              is_filter_yn
                            )
                            select 
                              :audienceSourceId, 
                              dil_id, 
                              dim_id, 
                              qti_id, 
                              qtg_id, 
                              percent_requested, 
                              number_requested, 
                              exceed_quota, 
                              percent_exceedable, 
                              number_exceedable, 
                              display_order, 
                              dependant_yn, 
                              is_filter_yn 
                            from 
                              ppl_assigned_quotas 
                            where 
                              aud_id = :audienceId 
                              and deleted_yn = 'N'";

            $this->db->statement($insertQuery, [
                'audienceSourceId' => $audienceSourceId,
                'audienceId' => $audienceId
            ]);
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage(), 10025, $ex);
        }

        $this->insertRedirect($audienceSourceId, $redirects->getComplete(), 'COMPLETE');
        $this->insertRedirect($audienceSourceId, $redirects->getFail(), 'FAIL');
        $this->insertRedirect($audienceSourceId, $redirects->getFraud(), 'FRAUD');
        $this->insertRedirect($audienceSourceId, $redirects->getQuotaFull(), 'QUOTA FULL');
        $this->insertRedirect($audienceSourceId, $redirects->getTerminate(), 'TERMINATE');
        $this->insertRedirect($audienceSourceId, $redirects->getAlreadyTaken(), 'ALREADY TAKEN');

        $result = $this->query([
            new Filter('id', Equality::EQUAL, $audienceSourceId)
        ], 1);

        if ($result->getPaginationInfo()->getTotalItems() < 1) {
            throw new \Exception('Unable to find registration after creation');
        }
        $registration = $result->getRegistrations()[0];

        return $registration;
    }

    private function insertRedirect(int $audienceSourceId, string $url, string $statusName)
    {
        $insertQuery = "insert into ppl_source_redirects
                        (
                          aus_id,
                          sss_id,
                          base_url
                        )
                        select 
                          :audienceSourceId, 
                          sss.id, 
                          :url 
                        from 
                          ppl_survey_status_types sst 
                          join ppl_survey_statuses sss on sss.sst_id = sst.id 
                        where sst.name = 'VENDOR REDIRECTS' 
                          and sss.name = :statusName";

        try {
            $this->db->statement($insertQuery, [
                'audienceSourceId' => $audienceSourceId,
                'url' => $url,
                'statusName' => $statusName
            ]);
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage(), 10026, $ex);
        }
    }

    private function getLinks(int $vendorId, int $audienceSourceId) : SurveyAccess
    {
        $sql = "select 
                  variable || vnd.token || '/' || aus.token link
                from 
                  app_globals glb, 
                  ppl_vendors vnd, 
                  ppl_audience_sources aus 
                where 
                  glb.name = 'TARGETED_AUDIENCE_SOURCE_URL' 
                  and vnd.id = :vendorId 
                  and aus.id = :audienceSourceId";

        $surveyAccess = null;

        try {
            $rows = $this->db->select($sql, [
                'vendorId' => $vendorId,
                'audienceSourceId' => $audienceSourceId
            ]);

            if (count($rows) < 1) {
                throw new \Exception("Unable to create survey links");
            }
            $row = $rows[0];
            $surveyAccess = new SurveyAccess($row->link, $row->link . '/?isTestUser=1');
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage(), 10027, $ex);
        }

        return $surveyAccess;
    }

}