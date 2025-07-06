<?php
\Illuminate\Support\Facades\Artisan::command('inspire', function () {
    $this->comment(app(\Illuminate\Foundation\Inspiring::class)->quote());
})->purpose('Display an inspiring quote');
