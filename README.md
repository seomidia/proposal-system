# Proposal System

Sistema em Laravel 10 para receber negociações do Kommo e gerar propostas comerciais.

## Instalação

1. Clone o projeto e copie `.env.example` para `.env` preenchendo as variáveis.
2. Instale as dependências `composer install`.
3. Execute as migrações `php artisan migrate --seed`.
4. Inicie o servidor `php artisan serve`.

## Webhook

Envie os dados da negociação para `/api/kommo/webhook`. A URL da proposta será retornada em JSON e preenchida no campo customizado do Kommo.

## Estrutura de Diretórios

```
.
├── app/
├── bootstrap/
│   └── cache/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   └── index.php
├── resources/
│   ├── js/
│   ├── lang/
│   ├── sass/
│   └── views/
├── routes/
│   ├── api.php
│   ├── auth.php
│   ├── web.php
│   └── console.php
├── storage/
│   ├── app/
│   ├── framework/
│   │   ├── cache/
│   │   ├── sessions/
│   │   └── views/
│   └── logs/
├── tests/
│   ├── Feature/
│   └── Unit/
├── .env.example
├── .gitattributes
├── .gitignore
├── artisan
├── composer.json
├── package.json
├── phpunit.xml
├── vite.config.js
└── README.md
```
