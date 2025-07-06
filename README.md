# Proposal System

Sistema em Laravel 10 para receber negociações do Kommo e gerar propostas comerciais.

## Instalação

1. Clone o projeto e copie `.env.example` para `.env` preenchendo as variáveis.
2. Instale as dependências `composer install`.
3. Execute as migrações `php artisan migrate --seed`.
4. Inicie o servidor `php artisan serve`.

## Webhook

Envie os dados da negociação para `/api/kommo/webhook`. A URL da proposta será retornada em JSON e preenchida no campo customizado do Kommo.
