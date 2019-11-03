<?php

namespace App\Jobs;

class PageParserJob extends Job
{
    protected $id;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(\GuzzleHttp\Client $client)
    {
        $domain = \App\Domain::find($this->id);
        try {
            $promise = $client->getAsync($domain->name);
            $promise->then(
                function($response) use ($domain) {                     
                    $domain->status_code = $response->getStatusCode();
                    $body = $response->getBody();
                    if (mb_detect_encoding($body) !== 'UTF-8') {
                        $domain->body = utf8_encode($body);
                    } else {
                        $domain->body = $body;
                    }
                    $domain->content_length = strlen($domain->body);
                    $domain->save();
                }
            );
            $promise->wait();
        } catch (\Exception $e) {
            $domain->status_code = 'ERROR CONNECTION!';
            $domain->save();
        }
        return;
    }
}
