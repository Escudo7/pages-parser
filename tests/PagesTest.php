<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class PagesTest extends TestCase
{

    public function testStartPageStatus()
    {
        $response = $this->call('GET', '/');
        $this->assertEquals(200, $response->status());
    }
}