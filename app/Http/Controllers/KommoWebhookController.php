<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Ramsey\Uuid\Type\Decimal;

class KommoWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->all();
        $leads = end($data['leads']['update']);
        $leadsId = $leads['id'];
        if (! $leadsId) {
            return response()->json(['error' => 'Missing leads id'], 422);
        }

        $custom_fields =  $leads['custom_fields'];
        $args = [
                'faturamento_medio_mensal' => (float) $custom_fields[0]['values'][0]['value'] ?? null,
                'faturamento_medio_anual' => (float) $custom_fields[1]['values'][0]['value'] ?? null,
                'quantidade_socios_contrato' => (int) $custom_fields[2]['values'][0]['value'] ?? null,
                'tributacao_federal' => $custom_fields[3]['values'][0]['value'] ?? null,
                'media_declaracoes_ano' => (int) $custom_fields[4]['values'][0]['value'] ?? null,
                'media_lancamentos_mes' => (int) $custom_fields[5]['values'][0]['value'] ?? [],
                'quantos_funcionarios' => (int) $custom_fields[6]['values'][0]['value'],
        ];


        $proposal = Proposal::updateOrCreate(
            ['kommo_lead_id' => $leadsId],
            $args
        );

        $proposal->proposal_url = 'https://maxxidoctor.com.br/proposta-maxxi-doctor/?proposta=' . $proposal->id;
        $proposal->save();

        $this->updateKommoDeal($leadsId, $proposal->proposal_url);

        return response()->json(['url' => $proposal->proposal_url]);
    }

    protected function updateKommoDeal(int $dealId, string $url): void
    {
        $fieldId = 795795;
        $baseUrl = 'https://mktamomaxxidoctorcombr.kommo.com';
        $token = $this->getToken();

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

    public function getToken()
    {
        $path = 'kommo_refresh_token.txt';
        $refreshToken = Storage::disk('local')->exists($path)
            ? trim(Storage::disk('local')->get($path))
            : env('KOMMO_REFRESH_TOKEN');

        $payload = json_encode([
            'client_id' => 'be166fe1-4ae5-4929-9676-5df1fe6ff4f2',
            'client_secret' => 'WlOrIOqTgcE8Cx6x5NyXtUJMfKeea5v0c7qAMJMLAJyr08Ur9BoFqWQkB7FNxiL6',
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'redirect_uri' => 'https://maxxidoctor.com.br/',
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://mktamomaxxidoctorcombr.kommo.com/oauth2/access_token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'accept: application/json',
                'content-type: application/json',
                'Cookie: server_time=1753045890; session_id=i5epmh0r22vk2sgtrlsi5egj9e; user_lang=en',
            ],
        ]);

        $response = json_decode(curl_exec($curl));

        curl_close($curl);

        if (isset($response->refresh_token)) {
            Storage::disk('local')->put($path, $response->refresh_token);
        }

        return $response->access_token ?? null;
    }
}
