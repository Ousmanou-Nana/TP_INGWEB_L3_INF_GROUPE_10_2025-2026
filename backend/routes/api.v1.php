<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


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

<?php

/*
|--------------------------------------------------------------------------
| Extrait à ajouter dans : backend/routes/api.v1.php
| Auteur  : MOHAMADOU DANDI MOHAMADOU (23B078FS)
| Branche : Feature/user-teachers-23B078FS
|--------------------------------------------------------------------------
|
| Coller ce bloc dans api.v1.php, à l'intérieur du groupe middleware
| auth:sanctum + admin, comme ceci :
|
|   Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
|       // ← coller ici
|   });
|
*/

use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('teachers')->group(function () {

    // GET    /api/v1/teachers               — Liste paginée + filtres
    Route::get('/',               [TeacherController::class, 'index']);

    // POST   /api/v1/teachers               — Créer un enseignant
    Route::post('/',              [TeacherController::class, 'store']);

    // GET    /api/v1/teachers/{id}          — Profil d'un enseignant
    Route::get('/{id}',           [TeacherController::class, 'show']);

    // PUT    /api/v1/teachers/{id}          — Modifier un enseignant
    Route::put('/{id}',           [TeacherController::class, 'update']);

    // DELETE /api/v1/teachers/{id}          — Supprimer (soft delete)
    Route::delete('/{id}',        [TeacherController::class, 'destroy']);

    // PATCH  /api/v1/teachers/{id}/block    — Bloquer
    Route::patch('/{id}/block',   [TeacherController::class, 'block']);

    // PATCH  /api/v1/teachers/{id}/unblock  — Débloquer
    Route::patch('/{id}/unblock', [TeacherController::class, 'unblock']);
});
