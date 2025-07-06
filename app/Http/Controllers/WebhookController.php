<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();
        $dealId = $data['id'] ?? null;
        if (!$dealId) {
            return response()->json(['error' => 'invalid payload'], 400);
        }

        $proposal = Proposal::updateOrCreate(
            ['kommo_deal_id' => $dealId],
            [
                'client_name' => $data['client_name'] ?? '',
                'client_email' => $data['client_email'] ?? '',
                'client_phone' => $data['client_phone'] ?? '',
                'amount' => $data['amount'] ?? 0,
                'due_date' => $data['due_date'] ?? null,
                'custom_fields' => $data['custom_fields'] ?? [],
            ]
        );

        $proposal->proposal_url = URL::to('/proposals/' . $proposal->id);
        $proposal->save();

        $customFieldId = config('kommo.proposal_url_field');
        if ($customFieldId) {
            Http::post('https://kommo.com/api/deals/' . $dealId . '/custom_fields/' . $customFieldId, [
                'value' => $proposal->proposal_url,
            ]);
        }

        return response()->json(['url' => $proposal->proposal_url]);
    }
}
