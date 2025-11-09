<?php

namespace App\Traits;

trait HttpResponse
{
    public function sendResponse(int $code = 200, string $message = 'Ok', mixed $data = [])
    {
        $response = [
            'code' => $code,
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    public function sendError(int $code = 500, string $message = 'Something wrong with server', mixed $errors = [], mixed $traces = [])
    {
        $response = [
            'code' => $code,
            'message' => $message
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        if (config('app.debug')) {
            if (!empty($traces)) {
                $response['traces'] = $traces;
            }
        }

        return response()->json($response, $code);
    }
}
