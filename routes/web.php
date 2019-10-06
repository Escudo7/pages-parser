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

$router->get('/', ['as' => 'domains.create', 'uses' => 'DomainController@create']);

$router->post('/domains', ['as' => 'domains.store', 'uses' => 'DomainController@store']);
    
$router->get('domains/{id}', ['as' => 'domains.show', 'uses' => 'DomainController@show']);

$router->get('domains', ['as' => 'domains.index', 'uses' => 'DomainController@index']);
