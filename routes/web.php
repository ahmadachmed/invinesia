<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// $router->post('/reset', 'UserController@sendResetToken');
$router->post('/login', 'UserController@login'); //login
$router->group(['middleware' => 'auth'], function() use($router){
    //user management
    $router->get('/users', 'UserController@index'); //get user
    $router->post('/users', 'UserController@store'); //create user(register)
    $router->get('/users/login', 'UserController@getUserLogin'); // get login info
    $router->get('/users/{id}', 'UserController@edit'); // get user by id
    $router->put('/users/{id}', 'UserController@update'); //edit user by id
    $router->delete('/users/{id}', 'UserController@destroy'); // delete user by id

    //event management
    $router->get('/event', 'EventController@index'); // get all event
    $router->post('/event', 'EventController@store'); //create event
    $router->get('/event/{id}', 'EventController@edit'); //get event by id
    $router->put('/event/{id}', 'EventController@update'); //edit event by id
    $router->delete('/event/{id}', 'EventController@destroy'); //delete event by id
    

    $router->post('/logout', 'UserController@logout'); //logout
});


