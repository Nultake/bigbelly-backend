<?php

namespace App\Helpers\JsonResponse;

class Response
{
    public bool $success;
    public array $payload;
    public int $status;
    public string $message;
    public array $headers;


    public function success($success)
    {
        $this->success = $success;

        return $this;
    }

    public function message($message)
    {
        $this->message = $message;

        return $this;
    }

    public function payload($payload)
    {
        $this->payload = $payload;

        return $this;
    }
    public function status($status)
    {
        $this->status = $status;

        return $this;
    }
    public function headers($headers)
    {
        $this->headers = $headers;

        return $this;
    }


    public function sendResponse()
    {
        $response = [
            'success' => $this->success,
            'message' => $this->message,
            'payload' => $this->payload
        ];

        return response($response, $this->status, $this->headers);
    }
}
