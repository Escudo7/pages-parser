<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\RequestException;

class PageParserJob extends Job
{
    protected $url;
    protected $id;
    protected $clientName;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($url, $id, $clientName = 'productionClient')
    {
        $this->url = $url;
        $this->id = $id;
        $this->clientName = $clientName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = app($this->clientName);
        
        try {
            $promise = $client->getAsync($this->url);
            $promise->then(
                function($response) {  
                    $body = utf8_encode($response->getBody());
                    $contentLength = strlen($body);
                    DB::table('domains')
                        ->where('id', $this->id)
                        ->update([
                            'content_length' => $contentLength,
                            'status_code' => $response->getStatusCode(),
                            'body' => $body
                        ]);
                }
            );
            $promise->wait();
        } catch (RequestException $e) {
            if ($e instanceof \GuzzleHttp\Exception\ConnectException) {
                $statusCode = "Bad connect!";
            } elseif ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
            } else {
                $statusCode = 'ERROR';
            }
            DB::table('domains')
                    ->where('id', $this->id)
                    ->update([
                        'status_code' => $statusCode
                    ]);
        }
        return;
    }
}
