<?php

use App\Jobs\SendDailySalesReportJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new SendDailySalesReportJob)->dailyAt('23:00');
