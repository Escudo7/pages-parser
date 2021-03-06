<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Jobs\PageParserJob;
use App\Jobs\SeoParserJob;

class DomainController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
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
        $url = $request['pagesAdress'];
        
        $validator = Validator::make($request->all(), [
            'pagesAdress' => 'required|url'
        ]);
        if ($validator->fails()) {
            $data = [
                'errors' => $validator->errors()->all(),
                'url' => $url
            ];
            return redirect()->route('domains.create', $data);
        }
        
        $domain = new \App\Domain();
        $domain->name = $url;
        $domain->save();
        $id = $domain->id;
        dispatch(new PageParserJob($id));
        dispatch(new SeoParserJob($id));
        return redirect()->route('domains.show', ['id' => $id]);
    }        

    public function show($id)
    {
        $domain = \App\Domain::findOrFail($id);
        return view('domain.show', ['domain' => $domain]);
    }
    
    public function index()
    {
        $domains = \App\Domain::paginate(10);
        return view('domain.index', ['domains' => $domains]);
    }
}
