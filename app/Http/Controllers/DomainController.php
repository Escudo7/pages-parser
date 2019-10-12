<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Jobs\PageParserJob;

class DomainController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(\GuzzleHttp\Client $client)
    {
        //
    }

    public function create(Request $request)
    {
        $data = [
            'errors' => $request['errors'],
            'url' => $request['url']
        ];
        return view('domain.create', $data);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pagesAdress' => 'required|url|unique:domains,name'
        ]);
        if ($validator->fails()) {
            $data = [
                'errors' => $validator->errors()->all(),
                'url' => $request['pagesAdress']
            ];
            return redirect()->route('domains.create', $data);
        }
        $url = $request['pagesAdress'];
        DB::table('domains')->insert(
            ['name' => $url]
        );
        $id = DB::table('domains')->max('id');
        dispatch(new PageParserJob($url, $id));
        return redirect()->route('domains.show', ['id' => $id]);     
    }        

    public function show($id)
    {
        $domain = DB::table('domains')->where('id', $id)->first();
        return view('domain.show', ['domain' => $domain]);
    }
    
    public function index()
    {
        $domains = DB::table('domains')->paginate(10);
        return view('domain.index', ['domains' => $domains]);
    }
}
