<?php

namespace App\Bootstrap;

use App\Modules\GptClient;
use App\Modules\RequestValidator;
use App\Modules\Response;

class Bootstrap
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function init()
    {
        $requestValidator = new RequestValidator();
        $requestValidator->validate();

        $gptClient = new GptClient($this->config->apiKey);
        $gptResponse = $gptClient->chatMessage($_POST['userName'], $_POST['message']);

        $response = new Response();
        $response->sendJson([
            'status' => 'ok',
            'message' => 'all good',
            'body' => $gptResponse
        ]);
    }
}