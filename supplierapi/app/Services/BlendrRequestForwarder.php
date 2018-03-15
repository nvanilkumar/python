<?php

namespace App\Services;

use App\Exceptions\InvalidArgumentException;
use App\Services\Blendr\Api;
use App\Services\Blendr\ErrorEnvelope;
use SebastianBergmann\GlobalState\RuntimeException;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Zend\Diactoros\Uri;

class BlendrRequestForwarder implements RequestForwarderInterface
{
    private $request;
    private $blendr;

    public function __construct(Request $request, Api $blendr)
    {
        $this->request = $request;
        $this->blendr = $blendr;
    }

    public function forward(string $path): Response
    {
        $psr7Factory = new DiactorosFactory();
        $httpFoundationFactory = new HttpFoundationFactory();

        $psr7Request = $psr7Factory->createRequest($this->request);

        $uri = new Uri($path);
        $uri = $uri->withQuery($psr7Request->getUri()->getQuery());

        $psr7Request = $psr7Request->withUri($uri);

        $psr7Response = $this->blendr->send($psr7Request);
        $symfonyResponse = $httpFoundationFactory->createResponse($psr7Response);

        $statusCode = $symfonyResponse->getStatusCode();

        if ($statusCode !== 200 && $statusCode !== 201) {
            $e = null;

            try {
                $e = ErrorEnvelope::fromJSON($symfonyResponse->getContent())->getError();
            } catch (InvalidArgumentException $ex) {
                throw new RuntimeException('status code: ' . $statusCode . ': unable to deserialize response: ' . $ex->getMessage());
            }
            throw new HttpException($statusCode, $e->getMessage(), null, [], intval($e->getCode()));
        }

        return $symfonyResponse;
    }
}