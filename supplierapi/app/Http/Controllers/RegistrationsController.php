<?php

namespace App\Http\Controllers;

use App\Exceptions\FilterResolutionException;
use App\Exceptions\OutOfBoundsException;
use App\Filters\Equality;
use App\Filters\Filter;
use App\Services\RegistrationService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegistrationsController extends Controller
{
    private $request;
    private $registrationService;


    public function __construct(Request $request, RegistrationService $registrationService)
    {
        $this->request = $request;
        $this->registrationService = $registrationService;
    }

    public function getRegistrations(): Response
    {
        $vendorId = $this->request->input('vendorId');

        $limit = $this->request->input('limit') ?? 500;
        $page = $this->request->input('page') ?? 1;

        $filters = [
            new Filter('vendorId', Equality::EQUAL, $vendorId)
        ];

        $filterStrings = $this->request->input('filters') ?? [];

        if (!is_array($filterStrings)) {
            throw new BadRequestHttpException('Expected \'filters\' parameter to be an array');
        }

        $filterWhiteList = [
            'id',
            'audienceId',
            'dateCreated',
            'intent',
            'contractedCPI',
            'totalCompletes'
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

        $result = null;

        try {
            $result = $this->registrationService->query($filters, $limit, $page);
        } catch (FilterResolutionException $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (OutOfBoundsException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $response = response()->json($result->getRegistrations());
        $response->headers->set('Total-Items', $result->getPaginationInfo()->getTotalItems());
        $response->headers->set('Total-Pages', $result->getPaginationInfo()->getTotalPages());
        $response->headers->set('Page', $result->getPaginationInfo()->getPage());

        return $response;
    }

    public function getRegistration(int $audienceSourceId): Response
    {
        $vendorId = $this->request->input('vendorId');

        $result = $this->registrationService->query([
            new Filter('id', Equality::EQUAL, $audienceSourceId),
            new Filter('vendorId', Equality::EQUAL, $vendorId)
        ], 1);

        if ($result->getPaginationInfo()->getTotalItems() < 1) {
            throw new NotFoundHttpException('Registration not found');
        }

        return response()->json($result->getRegistrations()[0]);
    }
}