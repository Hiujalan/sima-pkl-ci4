<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setAutoRoute(false);

$routes->get('/', 'Home::index');


$routes->group('api', function ($routes) {

    $routes->group('v1', [
        'namespace' => 'App\Controllers\Api\V1'
    ], function ($routes) {
        $routes->post('auth/login', 'AuthController::login');
        $routes->post('auth/refresh-token', 'AuthController::refreshToken');

        $routes->group('', ['filter' => 'jwt'], function ($routes) {

            $routes->get('auth/me', 'AuthController::me');
            $routes->post('auth/logout', 'AuthController::logout');

            $routes->resource('users', [
                'controller' => 'UserController',
                'except'     => ['new', 'edit'],
            ]);
        });
    });
});

/*
|--------------------------------------------------------------------------
| API CROS ORIGIN
|--------------------------------------------------------------------------
*/

$routes->options('api/(:any)', function () {
    return service('response')
        ->setStatusCode(200)
        ->setHeader('Access-Control-Allow-Origin', '*')
        ->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
});

/*
|--------------------------------------------------------------------------
| API DEFAULT Fallback
|--------------------------------------------------------------------------
*/


$routes->set404Override(function () {
    $request = service('request');

    if (str_starts_with($request->getPath(), 'api/')) {
        if (! $request->is('json')) {
            return api_error('Method not allowed', null, 405);
        }

        return api_not_found('Endpoint not found');
    }

    return view('errors/html/error_404');
});

$routes->add('api/(:any)', function () {
    return api_not_found('Endpoint not found');
});
