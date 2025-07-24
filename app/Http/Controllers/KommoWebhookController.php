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

        // Kommo sometimes returns the custom fields under the
        // `custom_fields_values` key. Build an associative array by field
        // id so differences in ordering between create and update payloads
        // don't break lookups.
        $customFields = $leads['custom_fields'] ?? $leads['custom_fields_values'] ?? [];
        $fieldsById = [];
        foreach ($customFields as $field) {
            if (isset($field['id']) && isset($field['values'][0]['value'])) {
                $fieldsById[$field['id']] = $field['values'][0]['value'];
            }
        }

        $args = [
            'client_name' => $fieldsById['796491'] ?? null,
            'faturamento_medio_mensal' => $this->parseBrazilianFloat($fieldsById['795809'] ?? null),
            'faturamento_medio_anual' => $this->parseBrazilianFloat($fieldsById['795811'] ?? null),
            'quantidade_socios_contrato' => isset($fieldsById['795813']) ? (int) $fieldsById['795813'] : null,
            'tributacao_federal' => $fieldsById['795815'] ?? null,
            'media_declaracoes_ano' => isset($fieldsById['795817']) ? (int) $fieldsById['795817'] : null,
            'media_lancamentos_mes' => isset($fieldsById['795819']) ? (int) $fieldsById['795819'] : null,
            'quantos_funcionarios' => isset($fieldsById['795821']) ? (int) $fieldsById['795821'] : null,
            'tipo_proposta' => $fieldsById['796409'] ?? null,
            'economia_por_ano' => isset($fieldsById['796411']) ? (int) $fieldsById['796411'] : null,
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

    public function  parseBrazilianFloat($value) {
        if ($value === null) {
            return null;
        }

        // Remove o ponto de milhar e troca a vírgula por ponto
        $value = str_replace(['.', ','], ['', '.'], $value);

        return (float) $value;
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
        $refreshToken = trim(Storage::disk('local')->get($path));

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
                'content-type: application/json'
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
