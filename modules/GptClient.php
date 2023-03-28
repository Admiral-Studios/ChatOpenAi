<?php

namespace App\Modules;

use GuzzleHttp\Client;

class GptClient
{
    private Client $client;

    public function __construct(string $apiKey)
    {
        $this->client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $apiKey,
            ]
        ]);
    }

    public function chatMessage(string $userName, string $message)
    {
        $context = $this->getContextFromJson($userName);

        $requestBody = [
            "model" => "gpt-3.5-turbo",
            "messages" => [
                ([
                    'role' => 'user',
                    'content' => (empty($context)) ? $message : $context . ' ' . $message,
                ])
            ],
            'user' => $userName,
        ];

        $response = $this->client->post('https://api.openai.com/v1/chat/completions', [
            'body' => json_encode($requestBody)
        ]);

        return json_decode($response->getBody()->getContents());
    }

    public function getContextFromJson(string $filename): string
    {
        $historyFilename = ROOT . "/history/{$filename}_history.json";

        JsonStorage::setFilename($historyFilename);

        $jsonHistory = JsonStorage::read();

        $context = '';

        if ( ! empty($jsonHistory)) {
            $lastElement = array_pop($jsonHistory);

            $context = $lastElement->lastUserText . ' ' . $lastElement->lastChatAnswer;
        }

        return $context;
    }
}