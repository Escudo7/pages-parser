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
    
    public function store(Request $request, $err, $standartClientName = 'productionClient')
    {
        $clientName = $request['clientName'] ?? $standartClientName;
        $validator = Validator::make($request->all(), [
            'pagesAdress' => 'required|url'
        ]);
        if ($validator->fails()) {
            $data = [
                'errors' => $validator->errors()->all(),
                'url' => $request['pagesAdress']
            ];
            return redirect()->route('domains.create', $data);
        }
        $url = $request['pagesAdress'];
        $domain = new \App\Domain();
        $domain->name = $url;
        $domain->save();
        $id = $domain->id;
        dispatch(new PageParserJob($url, $id, $clientName));
        dispatch(new SeoParserJob($url, $id));
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
