<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_basic_assertion(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
