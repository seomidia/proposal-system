<?php

namespace Tests\Feature;

use App\Models\Proposal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KommoWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_proposal_via_webhook(): void
    {
        $response = $this->postJson('/api/kommo/webhook', [
            'deal_id' => 1,
            'client_name' => 'John Doe',
            'amount' => 100,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('proposals', [
            'kommo_deal_id' => 1,
            'client_name' => 'John Doe',
        ]);
    }
}
