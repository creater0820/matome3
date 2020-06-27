<?php

namespace Tests\Unit;

use Tests\TestCase;
// use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
        $response = $this->json(
            'POST',
            '/api/comment/index',
            ['url_id' => 1],
        );
        $response->assertStatus(200);
        $data = $response->json();
        // dd($data);
        return $data;
    }
    /**
     * @depends testBasicTest 
     * @param $data 
     * @return mixed
     */
    public function test($data)
    {
        $response = $this->json(
            'POST',
            '/api/commit/store',
            [
                'url_id' => 1,
                'comment' => '',
                'name' => '名前',
            ],
        );
        // $response->assertStatus(200);
        $data = $response->json();
        dd($data);
    }
}
