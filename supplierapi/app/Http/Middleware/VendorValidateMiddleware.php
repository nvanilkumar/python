<?php

namespace App\Http\Middleware;

use App\Services\VendorService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class VendorValidateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    private $vendorService;

    public function __construct(VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }

    public function handle(Request $request, Closure $next)
    {
        $customIdHeader = $request->header('x-consumer-custom-id');

        if ($customIdHeader == null) {
            throw new BadRequestHttpException('vendorId not provided', null, 10008);
        }

        if (!is_numeric($customIdHeader)) {
            throw new BadRequestHttpException('Bad value for vendorId. Expected number, got: ' . $customIdHeader, null, 10009);
        }
        $vendorId = intval($customIdHeader);

        if (!$this->vendorService->exists($vendorId)) {
            throw new BadRequestHttpException('Vendor does not exist for ID ' . $vendorId, null, 10010);
        }

        $request->merge(['vendorId' => $vendorId]);

        return $next($request);
    }
}
