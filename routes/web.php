<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

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

$router->get('/', ['as' => 'start', function (Request $request) use ($router) {
    $data = [
        'errors' => $request['errors'],
        'url' => $request['url']
    ];
    return view('start', $data);
}]);

$router->post('/domains', ['as' => 'domains.store', function (Request $request) use ($router) {
    $validator = Validator::make($request->all(), [
        'pagesAdress' => 'required|url'
    ]);
    if ($validator->fails()) {
        $data = [
            'errors' => $validator->errors()->all(),
            'url' => $request['pagesAdress']
        ];
        return redirect()->route('start', $data);
    }
    DB::table('domains')->insert(['name' => $request['pagesAdress']]);
    $id = DB::table('domains')->max('id');
    return redirect()->route('domains.show', ['id' => $id]);
}]);

$router->get('domains/{id}', ['as' => 'domains.show', function ($id) use ($router) {
    $domain = DB::table('domains')->where('id', $id)->first();
    return view('domains', ['domain' => $domain]);
}]);