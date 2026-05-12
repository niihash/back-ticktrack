<?php

use App\Http\Controllers\Api\Central\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::post('/register', [RegisterController::class, 'store']);
    });
}
