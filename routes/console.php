<?php
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\{Artisan, Schedule};

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:fetch-sales')->everyMinute();
Schedule::command('app:fetch-orders')->everyFiveMinutes();
Schedule::command('app:fetch-stocks')->everyTenMinutes();
Schedule::command('app:fetch-incomes')->everyThirtyMinutes();
