<?php

namespace App\Helpers\JsonResponse;

class JsonResponse
{
    const HEADERS = [
        'Content-Type' => 'application/json',
        'charset'      => 'utf-8'
    ];

    public static function success($message = 'Request has succeed', $payload = [])
    {
        $response = new Response();

        return $response->success(true)
            ->message($message)
            ->payload($payload)
            ->status(200)
            ->headers(self::HEADERS)
            ->sendResponse();
    }

    public static function error($message = 'Request has failed', $payload = [])
    {
        $response = new Response();


        return $response->success(false)
            ->message($message)
            ->payload($payload)
            ->status(500)
            ->headers(self::HEADERS)
            ->sendResponse();
    }
}
