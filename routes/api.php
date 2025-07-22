<?php

use App\Http\Controllers\KommoWebhookController;
use App\Http\Controllers\Api\ProposalApiController;
use App\Http\Controllers\Api\AuthTokenController;
use Illuminate\Support\Facades\Route;

Route::post('/kommo/webhook', KommoWebhookController::class);
Route::post('/token', [AuthTokenController::class, 'store']);

Route::middleware('jwt')->group(function () {
    Route::get('/proposals/{proposal}', [ProposalApiController::class, 'show']);
});
