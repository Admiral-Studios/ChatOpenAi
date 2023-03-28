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

        $response = json_decode($response->getBody()->getContents());

        $this->saveContextToJson($userName, $message, $response);

        return $response;
    }

    public function saveContextToJson(string $userName, string $message, $responseContent)
    {
        $historyFilename = ROOT . "/history/{$userName}_history.json";

        $contextData = [
            'userId' => $responseContent->id,
            'lastUserText' => $message,
            'lastChatAnswer' => $responseContent->choices[0]->message->content,
            'date' => $responseContent->created
        ];

        JsonStorage::setFilename($historyFilename);

        if ( ! empty(JsonStorage::read())) {
            JsonStorage::append($contextData);
        } else {
            JsonStorage::store($contextData);
        }
    }

    public function getContextFromJson(string $filename): string
    {
        $historyFilename = ROOT . "/history/{$filename}_history.json";

        if ( ! file_exists($historyFilename)) {
            return '';
        }

        JsonStorage::setFilename($historyFilename);

        $jsonHistory = JsonStorage::read();

        $context = '';

        if (! empty($jsonHistory)) {
            foreach ($jsonHistory as $element) {
                $context .= $element->lastUserText . ' ' . $element->lastChatAnswer;
            }
        }

        return $context;
    }
}