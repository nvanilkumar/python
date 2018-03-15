<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});
$app->get('/key', function() use ($app) {
    return str_random(32);
});

$app->group(['prefix' => '/api/v1','namespace' => 'App\Http\Controllers', 'middleware' => ['vendor']], function() use ($app) {
    $app->get('/audiences', 'AudiencesController@getAudiences');
    $app->get('/audiences/{audienceId:\d+}', 'AudiencesController@getSingleAudience');
    $app->get('/audiences/{audienceId:\d+}/quotas', 'AudiencesController@getAudienceQuotas');
    $app->post('/registrations', 'AudiencesController@register');
    $app->get('/registrations', 'RegistrationsController@getRegistrations');
    $app->get('/registrations/{audienceSourceId:\d+}', 'RegistrationsController@getRegistration');

    $app->post('/users', 'UsersController@addUser');
    $app->patch('/users/{userId}', 'UsersController@updateUser');
    $app->put('/users/{userId}/demos', 'UsersController@addDemos');
    $app->get('/users/{userId}/surveys', 'UsersController@getSurveys');
});

$app->group(['prefix' => '/api/v1','namespace' => 'App\Http\Controllers'], function() use ($app) {
    $app->get('/documentation', 'DocumentationController@getSwagger');
});





