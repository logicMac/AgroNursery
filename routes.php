<?php

use App\Core\Router;

/** @var Router $router */

$router->get('', 'AuthController@loginForm');
$router->get('login', 'AuthController@loginForm');
$router->post('login', 'AuthController@login');
$router->post('logout', 'AuthController@logout');

$router->get('dashboard', 'DashboardController@index');

$router->get('sales', 'SalesController@index');
$router->get('sales/create', 'SalesController@create');
$router->post('sales/store', 'SalesController@store');
$router->get('sales/{id}', 'SalesController@show');

$router->get('products', 'ProductsController@index');
$router->get('products/create', 'ProductsController@create');
$router->post('products/store', 'ProductsController@store');
$router->get('products/{id}/edit', 'ProductsController@edit');
$router->post('products/{id}/update', 'ProductsController@update');

$router->get('batches', 'BatchesController@index');
$router->get('batches/create', 'BatchesController@create');
$router->post('batches/store', 'BatchesController@store');
$router->get('batches/{id}', 'BatchesController@show');
$router->get('batches/{id}/edit', 'BatchesController@edit');
$router->post('batches/{id}/update', 'BatchesController@update');

$router->get('quality', 'QualityController@index');
$router->get('quality/create', 'QualityController@create');
$router->post('quality/store', 'QualityController@store');

$router->get('inventory', 'InventoryController@index');
$router->post('inventory/adjust/{id}', 'InventoryController@adjust');
$router->post('inventory/stockin', 'InventoryController@stockIn');

$router->get('pricing', 'PricingController@index');
$router->get('pricing/create', 'PricingController@create');
$router->post('pricing/store', 'PricingController@store');
$router->post('pricing/{id}/delete', 'PricingController@delete');

$router->get('environment', 'EnvironmentController@index');
$router->get('environment/create', 'EnvironmentController@create');
$router->post('environment/store', 'EnvironmentController@store');

$router->get('traceability', 'TraceabilityController@index');
$router->get('traceability/{lot}', 'TraceabilityController@show');

$router->get('shrinkage', 'ShrinkageController@index');
$router->get('shrinkage/create', 'ShrinkageController@create');
$router->post('shrinkage/store', 'ShrinkageController@store');

$router->get('forecast', 'ForecastController@index');
$router->get('forecast/batch/{id}', 'ForecastController@batch');

$router->get('users', 'UsersController@index');
$router->get('users/create', 'UsersController@create');
$router->post('users/store', 'UsersController@store');
$router->post('users/{id}/toggle', 'UsersController@toggle');

$router->get('settings', 'SettingsController@index');
$router->post('settings/update', 'SettingsController@update');
$router->post('settings/test', 'SettingsController@test');

// AI feature endpoints
$router->post('ai/shrinkage/{id}', 'AIController@shrinkageRisk');
$router->post('ai/quality/{id}', 'AIController@qualityGrade');
$router->post('ai/pricing/{id}', 'AIController@dynamicPrice');
$router->post('ai/forecast/{id}', 'AIController@forecastDate');
