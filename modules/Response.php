<?php

namespace App\Modules;

class Response
{
    public function sendJson(array $body = [], int $statusCode = 200): void
    {
        $response = new \GuzzleHttp\Psr7\Response($statusCode, [
            'Content-Type' => 'application/json',
        ], json_encode($body));

        foreach ($response->getHeaders() as $header => $value) {
            header($header . ': ' . $value[0]);
        }

        http_response_code($response->getStatusCode());

        echo $response->getBody();

        exit;
    }
}