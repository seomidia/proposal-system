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
                'client_name' =>  $custom_fields[1]['values'][0]['value'] ?? null,
                'faturamento_medio_mensal' => (float) $custom_fields[2]['values'][0]['value'] ?? null,
                'faturamento_medio_anual' => (float) $custom_fields[3]['values'][0]['value'] ?? null,
                'quantidade_socios_contrato' => (int) $custom_fields[4]['values'][0]['value'] ?? null,
                'tributacao_federal' => $custom_fields[5]['values'][0]['value'] ?? null,
                'media_declaracoes_ano' => (int) $custom_fields[6]['values'][0]['value'] ?? null,
                'media_lancamentos_mes' => (int) $custom_fields[7]['values'][0]['value'] ?? [],
                'quantos_funcionarios' => (int) $custom_fields[8]['values'][0]['value'],
                'tipo_proposta' =>  $custom_fields[9]['values'][0]['value'],
                'economia_por_ano' => (int) $custom_fields[10]['values'][0]['value'],
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
            : 'def502002121200e81ca89ceff67038fb149e2ddd26f8ab8771b3eb949fcc61ee7ea6b3bdc9815c594507164ba204a95261ff330d3a5ece809361ec2ac1de633c92d6e55a9101ba177ac976b06454957cafa03f9292635b8347167b498c9fde0f2a133575cffcab948b10a22ce60045d91ce7ec7db1fad35d52abb80fcea2a002d6126504e200fccf2773e5fb1fe3cf77ca50e3ae847feef2ac385addc93de3c46d140aa0f853d295c6c70ca40288c8016bd092f3ae0d67c0ae043e0fbe6f95d1428479d4adfe0c95457bc870dcc11bd8ad96de7316ca1ef0cce5cc5837785605025edcddb3c8f210e72ba31bbbd1942d49effce010c28a904807823fa46a68f3a5a2870b34583bce1b9c72c6f26c155e61535105a3239c1e9dae5a47301608c76f78fe7f5ca06ec818789db9d687949d6e5d76014dd1ec522f753e294e98c05059a377065e89a5d2501ef9c051a02da17a1c587fdf418ee4185ffbf65b17e64ab366e4a7b662bae2abd3b1cb3bb82e9ccd895b93bd603d93161fa2b36f5471649920736646d3c5b56fae334f26972dc5032d96f5b801185d593020a09b53941623c9b6f14b52d92f106fe55464aa77657ea6f7a67e8e14472ab3b72ddbe547ff1f8135b949df31a36c916ecc5a486625666a09a7fa8e92cc7402a521a4b7b10a034bc92e5c16effda0d8baa8bd0';

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
