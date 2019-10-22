<?php

namespace App\Jobs;

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
                    $domain = \App\Domain::find($this->id);                   
                    $domain->status_code = $response->getStatusCode();
                    $domain->body = utf8_encode($response->getBody());
                    $domain->content_length = strlen($domain->body);
                    $domain->save();
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
            $domain = \App\Domain::find($this->id);
            $domain->status_code = $statusCode;
            $domain->save();
        }
        return;
    }
}
