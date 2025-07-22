<?php
/**
 * Plugin Name: Proposta API Shortcodes
 * Description: Obtem informacoes de propostas da API do Laravel usando o parametro "proposta" na URL. Fornece shortcodes individuais para cada campo.
 * Version: 1.1.0
 */

$sandbox  = true;
$endpoint = 'https://maxxidoctor.com.br';
if($sandbox){
    $endpoint = 'https://f56f794d8927.ngrok-free.app';
}

define('PROPOSTA_API_URL',$endpoint);

function getToken(){
    $token = get_transient('proposta_api_token');
    if ($token !== false) {
        return $token;
    }

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => PROPOSTA_API_URL . '/api/token',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 10,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => json_encode([
          'email' => 'user@demo.com',
          'password' => 'password',
      ]),
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));

    $raw = curl_exec($curl);
    if (curl_errno($curl)) {
        curl_close($curl);
        return '';
    }
    curl_close($curl);

    $response = json_decode($raw);
    if (isset($response->token)) {
        set_transient('proposta_api_token', $response->token, HOUR_IN_SECONDS);
        return $response->token;
    }
    return '';
}

if (!defined('PROPOSTA_API_TOKEN')) {
    define('PROPOSTA_API_TOKEN', getToken());
}

function proposta_fetch_data() {
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $id = isset($_GET['proposta']) ? intval($_GET['proposta']) : 0;

    if (!$id) {
        return [];
    }


    $api_url = PROPOSTA_API_URL . '/api/proposals/' . $id;

    $args = [];
    $args['headers'] = ['Authorization' => 'Bearer ' . PROPOSTA_API_TOKEN];
    $response = wp_remote_get($api_url, $args);
    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        return [];
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    if (!is_array($data)) {
        return [];
    }

    $cache = $data;
    return (object) $data;
}

function proposta_field_shortcode($field) {
    $data = proposta_fetch_data();
    if (!$data) {
        return '';
    }
    $value = $data[$field] ?? '';
    if (is_array($value)) {
        $value = json_encode($value);
    }
    return esc_html($value);
}

function proposta_custom_field_shortcode($atts) {
    $atts = shortcode_atts(['campo' => ''], $atts);
    $data = proposta_fetch_data();
    if (!$data || !$atts['campo']) {
        return '';
    }
    $custom = $data['custom_fields'] ?? [];
    $value = $custom[$atts['campo']] ?? '';
    if (is_array($value)) {
        $value = json_encode($value);
    }
    return esc_html($value);
}

function proposta_register_field_shortcodes() {
    $fields = [
        'id',
        'client_name',
        'client_email',
        'client_phone',
        'amount',
        'due_date',
        'kommo_lead_id',
        'faturamento_medio_mensal',
        'faturamento_medio_anual',
        'quantidade_socios_contrato',
        'tributacao_federal',
        'media_declaracoes_ano',
        'media_lancamentos_mes',
        'quantos_funcionarios',
        'proposal_url',
        'tipo_proposta',
        'economia_por_ano',
    ];

    foreach ($fields as $field) {
        add_shortcode('proposta_' . $field, function() use ($field) {
            return proposta_field_shortcode($field);
        });
    }

    add_shortcode('proposta_custom', 'proposta_custom_field_shortcode');
}
add_action('init', 'proposta_register_field_shortcodes');

function getPropost($tipo_proposta){
    switch ($tipo_proposta) {
        case 'Contabilidade':
            return 'bloco1';
        break;
        case 'Assessoria na Apuração dos Tributos e Declarações':
            return 'bloco2';
        break;
        case 'Gestão Financeira':
            return 'bloco3';
        break;
        case 'Assessoria em Departamento Pessoal':
            return 'bloco4';
        break;
        case 'Livro Caixa (Pessoa Física)':
            return 'bloco5';
        break;
        case 'Assessoria Adicional':
            return 'bloco6';
        break;
        default:
            return '';
    }
}

function jstext()
{
    $data = proposta_fetch_data();
    if (!$data) {
        return;
    }
    $formatter = new NumberFormatter('pt_BR', NumberFormatter::CURRENCY);
    $proposta = getPropost($data->tipo_proposta);

    echo "
        <script>
        setTimeout(function(){
            jQuery('#nome_empresa h1').text('".esc_js($data->client_name)."');
            jQuery('#economiavalor p').text('Economize ".esc_js($formatter->formatCurrency($data->economia_por_ano, 'BRL'))."/ano');
            jQuery('.".esc_js($proposta)."').show();
        },1000);
        </script>
        <style>
        .bloco1,.bloco2,.bloco3,.bloco4,.bloco5,.bloco6{
            display:none
        }
        </style>
    ";
}
add_action('wp_head', 'jstext');
