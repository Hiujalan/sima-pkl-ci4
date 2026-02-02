<?php

namespace App\Filters;

use App\Models\DomainModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class CorsFilter implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        if ($request->getMethod() === 'options') {
            $response = service('response');
            return $this->setCorsHeaders($response);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $this->setCorsHeaders($response);
    }

    private function setCorsHeaders(ResponseInterface $response)
    {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

        if ($origin) {
            $allowedOrigins = [
                'localhost:3000',
            ];

            $origin = rtrim($origin, '/');

            if (in_array($origin, $allowedOrigins, true)) {
                $response->setHeader('Access-Control-Allow-Origin', $origin);
                $response->setHeader('Vary', 'Origin');
            }
        }

        $response
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
            ->setHeader(
                'Access-Control-Allow-Headers',
                'Content-Type, Authorization, X-Requested-With'
            )
            ->setHeader('Access-Control-Allow-Credentials', 'true');

        return $response;
    }
}
