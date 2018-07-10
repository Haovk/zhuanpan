<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    $router->resource('Turntable', TurntableController::class);
    $router->resource('PrizeLog', PrizeLogController::class);
    $router->resource('TurntableUser', TurntableUserController::class);
    $router->post('PrizeLog/prizegive', 'PrizeLogController@prizegive');
});
