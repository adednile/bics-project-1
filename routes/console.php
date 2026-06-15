<?php

use App\Console\Commands\CalculateDailyPenalties;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('chama:penalties', function () {
    $this->call(CalculateDailyPenalties::class);
})->purpose('Apply daily penalties');
