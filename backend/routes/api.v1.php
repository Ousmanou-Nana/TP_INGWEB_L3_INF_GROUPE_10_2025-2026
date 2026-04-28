<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;


Route::get("/status", function (): JsonResponse {

    $dbStatus = 'ok';
    $mysqlVersion = null;

    try {
        DB::connection()->getPdo();
        $mysqlVersion = DB::select("select version() as v")[0]->v;
    } catch (Exception $e) {
        $dbStatus = 'error:'.$e->getMessage();
    }

    return response()->json([
        'api_status' => 'ok',
        'database_status' => $dbStatus,
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'mysql_version' => $mysqlVersion,
        'server_time' => now()->toDateTimeString(),
    ], 200);
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});