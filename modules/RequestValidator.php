<?php

namespace App\Modules;

class RequestValidator
{
    public function validate(): void
    {
        $this->validateMethodPost();
        $this->validatePostParams();
    }

    public function validateMethodPost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = new Response();
            $response->sendJson([
                'status' => 'error',
                'message' => 'only POST method available'
            ]);
        }
    }

    public function validatePostParams(): void
    {
        if ( ! isset($_POST['userName']) && ! isset($_POST['message'])) {
            $response = new Response();
            $response->sendJson([
                'status' => 'error',
                'message' => 'userName or message not provided',
            ], 400);
        }
    }
}