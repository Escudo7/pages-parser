<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use DiDom\Document;

class SeoParserJob extends Job
{
    protected $url;
    protected $id;
    protected $seoParserName;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($url, $id, $seoParserName = 'seoParser')
    {
        $this->url = $url;
        $this->id = $id;
        $this->seoParserName = $seoParserName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $document = app($this->seoParserName);
            $document->loadHtmlFile($this->url);
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
        
        DB::table('domains')
            ->where('id', $this->id)
            ->update([
                'heading' => $heading,
                'keywords' => $keywords,
                'description' => $description
                ]);
        return;
    }
}
