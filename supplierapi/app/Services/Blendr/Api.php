<?php

namespace App\Services\Blendr;

use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Api
{
    private $client;
    /** @var Auth */
    private $auth;

    public function __construct(string $host)
    {
        $this->client = new Client([
            'base_uri' => $host,
            'http_errors' => false
        ]);

        $this->auth = null;
    }

    public function setAuth(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function send(RequestInterface $request): ResponseInterface
    {

        if (!$request->hasHeader('Content-Type')) {
            $request = $request->withHeader('Content-Type', 'application/json');
        }

        if ($this->auth !== null) {
            $request = $request->withHeader('Authorization', 'Basic ' . base64_encode($this->auth->getUsername() . ':' . $this->auth->getPassword()));
        }
        return $this->client->send($request);
    }
}