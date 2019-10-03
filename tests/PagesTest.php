<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagesTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function testStartPages()
    {
        $this->assertEquals(0, DB::table('domains')->count());
        $response = $this->call('GET', route('start'));
        $this->assertEquals(200, $response->status());
    }

    public function testDomainsStoreWithCorrectRequest()
    {   
        $this->assertEquals(0, DB::table('domains')->count());
        $data = ['pagesAdress' => 'http://lumen.laravel.com'];
        $response = $this->call('POST', route('domains.store'), $data);
        $this->seeStatusCode(302);
        $this->assertEquals(1, DB::table('domains')->count());
        $this->seeInDatabase('domains', ['name' => 'http://lumen.laravel.com']);
    }

    public function testDomainsStoreWithIncorrectRequest()
    {
        $this->assertEquals(0, DB::table('domains')->count());
        $data = ['pagesAdress' => 'lumen.laravel.com'];
        $response = $this->call('POST', route('domains.store'), $data);
        $this->seeStatusCode(302);
        $this->assertEquals(0, DB::table('domains')->count());
    }

    public function testDomainsIndex()
    {
        $this->assertEquals(0, DB::table('domains')->count());
        $data = ['pagesAdress' => 'http://lumen.laravel.com'];
        $response = $this->call('POST', route('domains.store'), $data);
        $response = $this->call('GET', route('domains.index'));
        $this->assertEquals(200, $response->status());
    }
}