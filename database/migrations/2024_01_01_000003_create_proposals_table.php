<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kommo_lead_id')->unique();
            $table->string('client_name')->nullable();
            $table->decimal('faturamento_medio_mensal', 10, 2)->nullable();
            $table->decimal('faturamento_medio_anual', 10, 2)->nullable();
            $table->integer('quantidade_socios_contrato')->nullable();
            $table->string('tributacao_federal')->nullable();
            $table->integer('media_declaracoes_ano')->nullable();
            $table->integer('media_lancamentos_mes')->nullable();
            $table->integer('quantos_funcionarios')->nullable();
            $table->string('proposal_url')->nullable();
            $table->json('tipo_proposta')->nullable();
            $table->string('economia_por_ano')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
