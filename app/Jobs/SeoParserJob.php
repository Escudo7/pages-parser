<?php

namespace App\Jobs;

use DiDom\Document;

class SeoParserJob extends Job
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
    public function handle(\DiDom\Document $document)
    {
        $domain = \App\Domain::find($this->id);
        $html = $domain->body;
        try {
            $document->loadHtml($html);
        } catch (\Exception $e) {
            return;
        }
        
        $heading = $document->has('h1') ?
            $document->find('h1')[0]->text() :
                '';
        
        if ($document->has('meta[name=keywords]')) {
            $element = $document->find('meta[name=keywords]')[0];
            $keywords = $element->hasAttribute('content') ? 
                $element->getAttribute('content') :
                '';
        } else {
            $keywords = '';
        }

        if ($document->has('meta[name=description]')) {
            $element = $document->find('meta[name=description]')[0];
            $description = $element->hasAttribute('content') ?
                $element->getAttribute('content') :
                '';
        } else {
            $description = '';
        }
        $domain->heading = $heading;
        $domain->keywords = $keywords;
        $domain->description = $description;
        $domain->save();
        return;
    }
}
