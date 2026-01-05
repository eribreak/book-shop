<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

abstract class Controller
{
    protected function successResponse($data, $message = 'Success', $status = Response::HTTP_OK)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function errorResponse($message = 'Error', $status = Response::HTTP_BAD_REQUEST, $errors = null)
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }
}
