<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
// use Illuminate\Support\Facades\Schedule;
// use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// 🔥 OTP Cleanup: Har ghante check karega aur 24 ghante purane OTPs delete kar dega
// Schedule::call(function () {
//     DB::table('otps')->where('created_at', '<', now()->subHours(24))->delete();
// })->hourly();