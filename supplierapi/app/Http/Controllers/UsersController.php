<?php

namespace App\Http\Controllers;

use App\Services\RequestForwarderInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    private $request;
    private $forwarder;

    public function __construct(Request $request, RequestForwarderInterface $forwarder)
    {
        $this->request = $request;
        $this->forwarder = $forwarder;
    }

    public function addUser(): Response
    {
        $vendorId = $this->request->input('vendorId');

        return $this->forwarder->forward(sprintf('services/vendors/%d/users', $vendorId));
    }

    public function updateUser(string $userId): Response
    {
        $vendorId = $this->request->input('vendorId');

        return $this->forwarder->forward(sprintf('services/vendors/%d/users/%s', $vendorId, $userId));
    }

    public function addDemos(string $userId): Response
    {
        $vendorId = $this->request->input('vendorId');

        return $this->forwarder->forward(sprintf('services/vendors/%d/users/%s/demos', $vendorId, $userId));
    }

    public function getSurveys(string $userId): Response
    {
        $vendorId = $this->request->input('vendorId');

        return $this->forwarder->forward(sprintf('services/vendors/%d/users/%s/surveys', $vendorId, $userId));
    }
}