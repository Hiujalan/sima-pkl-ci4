<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class CorsFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if ($request->getMethod() === 'options') {
            return $this->setCorsHeaders(service('response'))
                ->setStatusCode(200);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader('X-CORS-FILTER', 'ACTIVE');
        return $this->setCorsHeaders($response);
    }

    private function setCorsHeaders(ResponseInterface $response)
    {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

        if ($origin === 'http://localhost:3000') {
            $response
                ->setHeader('Access-Control-Allow-Origin', $origin)
                ->setHeader('Vary', 'Origin')
                ->setHeader('Access-Control-Allow-Credentials', 'true');
        }

        return $response
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
            ->setHeader(
                'Access-Control-Allow-Headers',
                'Content-Type, Authorization, X-Requested-With'
            );
    }
}
