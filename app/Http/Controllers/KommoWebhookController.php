<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

        $proposal->proposal_url = URL::to('/proposals/' . $proposal->id);
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
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://mktamomaxxidoctorcombr.kommo.com/oauth2/access_token',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'
    {
    "client_id": "be166fe1-4ae5-4929-9676-5df1fe6ff4f2",
    "client_secret": "WlOrIOqTgcE8Cx6x5NyXtUJMfKeea5v0c7qAMJMLAJyr08Ur9BoFqWQkB7FNxiL6",
    "grant_type": "refresh_token",
    "refresh_token": "def502004c96474c833b1db52ce59f96c5b4dbdf852549850dcd4320f20b79561d10ac0534b080111f7cf6a9d47fb4e4d1808cb8babdcd7e757e5b5fd4df36d4e6ae396231b824f9e904c5735cbf290394563aa06a5e4f05b37b1932a8c49f0b4363a785296b712ffe672c1726b8aef34df5e52544f735e97f1253d8677c023298c3dac0e74f30d8854f3e327641d3819afde083738ce144dd62ef73bb67e1437023d144a8741233388726abe4c63697ed5adfb7fc96100b5638ba4b2b482389066d7f41a53e405cfa3ed21952fd56384ab2b6c703d449177b970b25df4782f025760509494e44746d00d1eb31ff1fe8b562a54e6b63f8e5659d4300b0a71c16d627956229e15c2148bc03d0851ffbdcdf76b7fdaf8ffedc5dae272f920664fe4d121cc57eff719d7107f6a862ebe9510572bb5920aa6ac7706c485a9a8c2374689d986ff93bbde096ca49d5be68d4afe1babea2bf256ae32b93711b4b8d7a5de4e89d2973af7e0723e4f10f8e62881447a904ca2a6ed4edc149602e90480b8101a9cac8f9faddc46c127af94a3de1653a593c5a0358d31a97d87dbf623a02c611b6dada750ac8a197455088d8a2e8baa553ef0ac990f0de0876785464dc78ca804c040e965eadf379ce5aa58046d5ccd9190d7479ec7fca22721f042858fb8972d7ed086050ec406a36dd4c13f8",
    "redirect_uri": "https://maxxidoctor.com.br/"
    }
    ',
    CURLOPT_HTTPHEADER => array(
        'accept: application/json',
        'content-type: application/json',
        'Cookie: server_time=1753045890; session_id=i5epmh0r22vk2sgtrlsi5egj9e; user_lang=en'
    ),
    ));

    $response = json_decode(curl_exec($curl));

    curl_close($curl);
    return $response->access_token;
    }
}
