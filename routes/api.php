<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

Route::post('/kommo/webhook', [WebhookController::class, 'handle']);
