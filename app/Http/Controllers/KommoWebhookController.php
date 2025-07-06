<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class KommoWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->all();
        $dealId = $data['id'] ?? $data['deal_id'] ?? null;
        if (! $dealId) {
            return response()->json(['error' => 'Missing deal id'], 422);
        }

        $proposal = Proposal::updateOrCreate(
            ['kommo_deal_id' => $dealId],
            [
                'client_name' => $data['client_name'] ?? null,
                'client_email' => $data['client_email'] ?? null,
                'client_phone' => $data['client_phone'] ?? null,
                'amount' => $data['amount'] ?? null,
                'due_date' => $data['due_date'] ?? null,
                'custom_fields' => $data['custom_fields'] ?? [],
            ]
        );

        $proposal->proposal_url = URL::to('/proposals/' . $proposal->id);
        $proposal->save();

        $this->updateKommoDeal($dealId, $proposal->proposal_url);

        return response()->json(['url' => $proposal->proposal_url]);
    }

    protected function updateKommoDeal(int $dealId, string $url): void
    {
        $fieldId = env('KOMMO_CUSTOM_FIELD_PROPOSAL_URL_ID');
        $baseUrl = rtrim(env('KOMMO_BASE_URL'), '/');
        $token = env('KOMMO_ACCESS_TOKEN');

        if (! $fieldId || ! $baseUrl || ! $token) {
            return;
        }

        $payload = [
            'custom_fields_values' => [
                [
                    'field_id' => (int) $fieldId,
                    'values' => [ ['value' => $url] ],
                ],
            ],
        ];

        Http::withToken($token)
            ->patch("{$baseUrl}/api/v4/leads/{$dealId}", $payload);
    }
}
