<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Tenant\AuthController;
use App\Http\Controllers\Api\Tenant\EmployeeController;
use App\Http\Controllers\Api\Tenant\NotificationController;
use App\Http\Controllers\Api\Tenant\TimeEntryController;
use App\Http\Controllers\Api\Tenant\UserController;
use App\Http\Controllers\Api\Tenant\WorkScheduleController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });

    Route::prefix('api')->group(function () {

        Route::post('/login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);

            Route::apiResource('/users', UserController::class);
            Route::apiResource('/employee', EmployeeController::class);
            Route::apiResource('/work-schedules', WorkScheduleController::class);
            Route::apiResource('/time-entries', TimeEntryController::class);

            Route::get('/notifications', [NotificationController::class, 'index']);
            Route::get('/notifications/{notification}', [NotificationController::class, 'show']);
            Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
            Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
        });
    });
});

Route::group(['prefix' => config('sanctum.prefix', 'sanctum')], static function () {
    Route::get('/csrf-cookie', [CsrfCookieController::class, 'show'])
        ->middleware([
            'web',
            //'universal',
            InitializeTenancyByDomain::class,
        ])->name('sanctum.csrf-cookie');
});
