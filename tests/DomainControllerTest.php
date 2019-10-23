<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $this->assertEquals(0, DB::table('domains')->count());
        $data = ['pagesAdress' => 'http://lumen.laravel.com'];
        $this->post(route('domains.store'), $data);
        $this->seeStatusCode(302);
        $this->assertEquals(1, DB::table('domains')->count());
        $this->seeInDatabase('domains', ['name' => 'http://lumen.laravel.com']);
    }

    public function testStoreWithIncorrectRequest()
    {
        $this->assertEquals(0, DB::table('domains')->count());
        $data = ['pagesAdress' => 'lumen.laravel.com'];
        $this->post(route('domains.store'), $data);
        $this->seeStatusCode(302);
        $this->assertEquals(0, DB::table('domains')->count());
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
        $data1 = ['pagesAdress' => 'http://lumen.laravel.com'];
        $this->assertEquals(0, DB::table('domains')->count());
        $this->post(route('domains.store'), $data1);
        $id = DB::table('domains')->max('id');
        dispatch(new PageParserJob($data1['pagesAdress'], $id, $clientName));
        $this->assertEquals(1, DB::table('domains')->count());
        $this->seeInDatabase('domains', ['body' => 'body']);
        $this->seeInDatabase('domains', ['status_code' => 200]);
        $this->seeInDatabase('domains', ['content_length' => 4]);
    }
}