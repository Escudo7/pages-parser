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

    public function __construct($id, $clientName)
    {
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
        $domain = \App\Domain::find($this->id);
        try {
            $promise = $client->getAsync($domain->name);
            $promise->then(
                function($response) use ($domain) {                     
                    $domain->status_code = $response->getStatusCode();
                    $domain->body = mb_convert_encoding($response->getBody(), "UTF-8");
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
