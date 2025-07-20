<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'kommo_lead_id',
        'faturamento_medio_mensal',
        'faturamento_medio_anual',
        'quantidade_socios_contrato',
        'tributacao_federal',
        'media_declaracoes_ano',
        'media_lancamentos_mes',
        'quantos_funcionarios',
        'proposal_url'
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'due_date' => 'date',
    ];
}
