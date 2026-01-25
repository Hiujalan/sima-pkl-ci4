<?php

use CodeIgniter\HTTP\ResponseInterface;

if (!function_exists('api_response')) {
    function api_response(
        bool $status,
        string $message = 'OK',
        $data = null,
        $errors = null,
        int $code = 200
    ): ResponseInterface {
        $response = service('response');

        return $response->setStatusCode($code)->setJSON([
            'status' => $status,
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'errors' => $errors,
        ]);
    }
}

if (!function_exists('api_success')) {
    function api_success(
        $data = null,
        string $message = 'Success',
        int $code = 200
    ): ResponseInterface {
        return api_response(true, $message, $data, null, $code);
    }
}

if (!function_exists('api_error')) {
    function api_error(
        string $message = 'Error',
        $errors = null,
        int $code = 400
    ): ResponseInterface {
        return api_response(false, $message, null, $errors, $code);
    }
}

if (!function_exists('api_validation_error')) {
    function api_validation_error(
        $errors,
        string $message = 'Validation error',
        int $code = 422
    ): ResponseInterface {
        return api_response(false, $message, null, $errors, $code);
    }
}

if (!function_exists('api_unauthorized')) {
    function api_unauthorized(
        string $message = 'Unauthorized',
        int $code = 401
    ): ResponseInterface {
        return api_response(false, $message, null, null, $code);
    }
}

if (!function_exists('api_forbidden')) {
    function api_forbidden(
        string $message = 'Forbidden',
        int $code = 403
    ): ResponseInterface {
        return api_response(false, $message, null, null, $code);
    }
}

if (!function_exists('api_not_found')) {
    function api_not_found(
        string $message = 'Not Found',
        int $code = 404
    ): ResponseInterface {
        return api_response(false, $message, null, null, $code);
    }
}

if (!function_exists('api_server_error')) {
    function api_server_error(
        string $message = 'Internal Server Error',
        int $code = 500
    ): ResponseInterface {
        return api_response(false, $message, null, null, $code);
    }
}
