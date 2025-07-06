<?php

use App\Http\Controllers\KommoWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/kommo/webhook', KommoWebhookController::class);
