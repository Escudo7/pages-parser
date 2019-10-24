<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use App\Jobs\PageParserJob;

class DomainControllerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function testStartPages()
    {
        $this->get(route('domains.create'));
        $this->seeStatusCode(200);
    }

    public function testStore()
    {   
        $this->assertEquals(0, \App\Domain::count());
        $data = ['pagesAdress' => 'http://lumen.laravel.com'];
        $this->post(route('domains.store'), $data);
        $this->seeStatusCode(302);
        $this->assertEquals(1, \App\Domain::count());
        $this->seeInDatabase('domains', ['name' => 'http://lumen.laravel.com']);
    }

    public function testStoreWithIncorrectRequest()
    {
        $data = ['pagesAdress' => 'lumen.laravel.com'];
        $this->post(route('domains.store'), $data);
        $this->seeStatusCode(302);
        $this->assertEquals(0, \App\Domain::count());
    }

    public function testShow()
    {
        $domain = new \App\Domain();
        $domain->name = 'http://lumen.laravel.com';
        $domain->save();
        $id = $domain->id;
        $this->get(route('domains.show', ['id' => $id]));
        $this->seeStatusCode(200);
    }

    public function testIndex()
    {
        $this->get(route('domains.index'));
        $this->seeStatusCode(200);
    }

    public function testParserJob()
    {
        $clientName = 'testClient';
        $statusCode = 200;
        $body = 'body';
        $this->app->bind($clientName, function ($app) use($statusCode, $body) {
            $headers = ['content-length' => 0];
            $protocol = '1.1';
            $mock = new GuzzleHttp\Handler\MockHandler([
                new GuzzleHttp\Psr7\Response($statusCode, $headers, $body, $protocol)
            ]);
            $handler = GuzzleHttp\HandlerStack::create($mock);
            return new GuzzleHttp\Client(['handler' => $handler]);
        });
        
        $domain = new \App\Domain();
        $domain->name = 'http://lumen.laravel.com';
        $domain->save();
        
        dispatch(new PageParserJob($domain->name, $domain->id, $clientName));
        $this->assertEquals(1, \App\Domain::count());
        $this->assertEquals(1, \App\Domain::where('body', $body)->count());
        $this->assertEquals(1, \App\Domain::where('status_code', $statusCode)->count());
        $this->assertEquals(1, \App\Domain::where('content_length', strlen($body))->count());
    }
}