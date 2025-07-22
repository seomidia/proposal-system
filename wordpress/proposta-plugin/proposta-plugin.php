<?php
/**
 * Plugin Name: Proposta API Shortcodes
 * Description: Obtem informacoes de propostas da API do Laravel usando o parametro "proposta" na URL. Fornece shortcodes individuais para cada campo.
 * Version: 1.1.0
 */

function proposta_fetch_data() {
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $id = isset($_GET['proposta']) ? intval($_GET['proposta']) : 0;
    if (!$id) {
        return [];
    }

    $api_url = 'https://maxxidoctor.com.br/api/proposals/' . $id;
    $args = [];
    if (defined('PROPOSTA_API_TOKEN')) {
        $args['headers'] = ['Authorization' => 'Bearer ' . PROPOSTA_API_TOKEN];
    }

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
    return $data;
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
