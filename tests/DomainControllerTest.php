<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use App\Jobs\PageParserJob;

class DomainControllerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->url = Faker\Factory::create()->url;
        $statusCode = 200;
        $body = file_get_contents(__DIR__ . '/testsFiles/testPage.html');
        
        $this->app->bind('GuzzleHttp\Client', function ($app) use ($statusCode, $body) {
            $headers = ['content-length' => 0];
            $protocol = '1.1';
            $mock = new GuzzleHttp\Handler\MockHandler([
                new GuzzleHttp\Psr7\Response($statusCode, $headers, $body, $protocol)
            ]);
            $handler = GuzzleHttp\HandlerStack::create($mock);
            return new GuzzleHttp\Client(['handler' => $handler]);
        });

        $this->testDataInDatabase = [
            'name' => $this->url,
            'status_code' => $statusCode,
            'body' => $body,
            'content_length' => strlen($body),
            'heading' => 'Test Page',
            'description' => 'test description',
            'keywords' => 'test keywords'
        ];
    }

    public function testStore()
    {   
        $this->assertEquals(0, \App\Domain::count());
        $this->post(route('domains.store'), ['pagesAdress' => $this->url]);
        $this->seeStatusCode(302);
        $this->seeInDatabase('domains', $this->testDataInDatabase);
    }

    public function testStoreWithIncorrectRequest()
    {
        $urlIncorrect = Faker\Factory::create()->domainName;
        $this->post(route('domains.store'), ['pagesAdress' => $urlIncorrect]);
        $this->seeStatusCode(302);
        $this->assertEquals(0, \App\Domain::count());
    }

    public function testShow()
    {
        $domain = factory(App\Domain::class)->create();
        $this->get(route('domains.show', ['id' => $domain->id]));
        $this->seeStatusCode(200);
    }  
}