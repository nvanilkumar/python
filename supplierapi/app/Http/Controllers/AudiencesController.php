<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidArgumentException;
use App\Exceptions\OutOfBoundsException;
use App\Filters\Equality;
use App\Filters\Filter;
use App\Models\RedirectInfo;
use App\Services\QuotaService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\Services\AudienceService;
use App\Services\RegistrationService;

class AudiencesController extends Controller
{
    private $request;
    private $audienceService;
    
    public function __construct(Request $request, AudienceService $audienceService)
    {
        $this->request = $request;
        $this->audienceService = $audienceService;
    }
    
    public function getAudiences()
    {
        $vendorId = $this->request->input('vendorId');
        
        $page  = $this->request->input('page') ?? 1;
        $limit = $this->request->input('limit') ?? 500;

        if (!is_numeric($limit)) {
            throw new BadRequestHttpException('Bad value for limit. Expected number, got: ' . $limit, null, 10003);
        }

        if (!is_numeric($page)) {
            throw new BadRequestHttpException('Bad value for page. Expected number, got: ' . $page, null, 10004);
        }

        $filters = [];

        $filterStrings = $this->request->input('filters') ?? [];

        if (!is_array($filterStrings)) {
            throw new BadRequestHttpException('Expected \'filters\' parameter to be an array');
        }

        $filterWhiteList = [
            'id',
            'cxNumber',
            'isRegistered',
            'name',
            'country',
            'language',
            'anticipatedIR',
            'anticipatedLOI',
            'actualIR',
            'actualLOI',
            'completesRemaining',
            'expectedEndDate',
            'category',
            'subCategory',
            'trueSample',
            'verity',
            'pii',
            'segment',
            'contractedCPI',
            'status'
        ];

        foreach ($filterStrings as $filterString) {

            try {
                $filter = Filter::parse($filterString);
            } catch (\InvalidArgumentException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }

            if (in_array($filter->getProperty(), $filterWhiteList)) {
                $filters[] = $filter;
            } else {
                throw new BadRequestHttpException('Property \'' . $filter->getProperty() . '\' is not filterable in filter \'' . $filterString . '\'');
            }
        }

        try {
            $result = $this->audienceService->query($vendorId, $filters, $limit, $page);
        } catch (OutOfBoundsException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e, 10007);
        }

        $response = response()->json($result->getAudiences());
        $response->headers->set('Total-Items', $result->getPaginationInfo()->getTotalItems());
        $response->headers->set('Total-Pages', $result->getPaginationInfo()->getTotalPages());
        $response->headers->set('Page', $result->getPaginationInfo()->getPage());

        return $response;
    }

    // To get Single Audience based on audienceId
    public function getSingleAudience(int $audienceId)
    {
        $vendorId = $this->request->input('vendorId');

        $audience = $this->audienceService->getAudience($vendorId, $audienceId);

        if ($audience == null) {
            throw new NotFoundHttpException('Audience does not exist for ID ' . $audienceId);
        }

        return response()->json($audience);
    }

    public function getAudienceQuotas(RegistrationService $registrationService, QuotaService $quotaService, int $audienceId)
    {
        $vendorId = $this->request->input('vendorId');

        if (!$this->audienceService->exists($audienceId)) {
            throw new NotFoundHttpException('Audience not found', null, 10098);
        }

        $result = $registrationService->query([
            new Filter('audienceId', Equality::EQUAL, $audienceId),
            new Filter('vendorId', Equality::EQUAL, $vendorId)
        ], 1);
        
        if ($result->getPaginationInfo()->getTotalItems() < 1) {
            $quotaGroups = $quotaService->getAudienceQuotas($audienceId);
        } else {
            $registration = $result->getRegistrations()[0];
            $quotaGroups = $quotaService->getAudienceSourceQuotas($registration->getId());
        }

        return response()->json($quotaGroups, 200);
    }

    public function register(RegistrationService $registrationService)
    {
        $vendorId = $this->request->input('vendorId');

        $audienceId = null;
        $intent = null;
        $completeLink = null;
        $failLink = null;
        $fraudLink = null;
        $quotaFullLink = null;
        $terminateLink = null;
        $alreadyTakenLink = null;

        try {
            $audienceId = $this->expectInt('audienceId', $this->request);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e, 10017);
        }

        try {
            $intent = $this->expectInt('intent', $this->request);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e, 10018);
        }

        try {
            $completeLink = $this->expectUrl('complete', $this->request);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e, 10019);
        }

        try {
            $failLink = $this->expectUrl('fail', $this->request);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e, 10020);
        }

        try {
            $fraudLink = $this->expectUrl('fraud', $this->request);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e, 10021);
        }

        try {
            $quotaFullLink = $this->expectUrl('quotaFull', $this->request);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e, 10022);
        }

        try {
            $terminateLink = $this->expectUrl('terminate', $this->request);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e, 10023);
        }

        try {
            $alreadyTakenLink = $this->expectUrl('alreadyTaken', $this->request);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e, 10024);
        }

        $audience = null;

        $audience = $this->audienceService->getAudience($vendorId, $audienceId);

        if ($audience == null) {
            throw new BadRequestHttpException("Audience not found", null, 10025);
        }

        if ($registrationService->exists($audience->getId(), $vendorId)) {
            throw new ConflictHttpException("Vendor is already registered for this audience", null, 10027);
        }

        if (!$this->audienceService->isRegisterable($audience->getId())) {
            throw new AccessDeniedHttpException("This audience does not allow registration", null, 10026);
        }

        $redirectInfo = new RedirectInfo($completeLink, $failLink, $fraudLink, $quotaFullLink, $terminateLink, $alreadyTakenLink);

        $surveyAccess = $registrationService->register($audience->getId(), $vendorId, $intent, $redirectInfo, $audience->getContractedCPI());

        return response()->json($surveyAccess, 201);
    }

    private function expectValue(string $property, Request $request)
    {
        $value = $request->input($property);

        if ($value === null) {
            throw new InvalidArgumentException('Must provide property ' . $property);
        }

        return $value;
    }

    private function expectInt(string $property, Request $request) : string
    {
        $value = $this->expectValue($property, $request);

        if (!is_int($value)) {
            throw new InvalidArgumentException('Invalid type for ' . $property . '. Expected integer, got: ' . gettype($value));
        }

        return $value;
    }

    private function expectUrl(string $property, Request $request) : string
    {
        $value = $this->expectValue($property, $request);

        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Invalid value for ' . $property . '. Expected valid URL, got: ' . $value);
        }

        return $value;
    }
}