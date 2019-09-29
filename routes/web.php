<?php

use Illuminate\Support\Facades\DB;

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
    return view('start');
});

$router->post('domains', function () use ($router) {
    $input = $_POST;
    DB::table('domains')->insert(['name' => $input['pagesAdress']]);
    $id = DB::table('domains')->max('id');
    return redirect()->route('domains.show', ['id' => $id]);
});

$router->get('domains/{id}', ['as' => 'domains.show', function ($id) use ($router) {
    $domain = DB::table('domains')->where('id', $id)->first();
    return view('domains', ['domain' => $domain]);
}]);