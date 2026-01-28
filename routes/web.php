<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. HERKESE AÇIK ROTALAR (Giriş yapmamış kullanıcılar sadece burayı görebilir)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// 2. KORUMALI ROTALAR (Sadece giriş yapmış kullanıcılar görebilir)
Route::middleware(['auth'])->group(function () {

    // SİTE ANA DİZİNİ (/) - Giriş yapılmadıysa otomatik login'e atar
    Route::get('/', [ProjectController::class, 'index'])->name('dashboard');

    // PROJE YÖNETİMİ
    Route::get('/projects', [ProjectController::class, 'list'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // ÇALIŞMA SAATLERİ KAYDETME (Kırmızı çizgiyi önlemek için tip belirttik)
    Route::post('/save-work-hours', function (Request $request) {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($user) {
            $user->increment('weekly_work_hours', (int)$request->seconds);
            return response()->json(['message' => 'Süre başarıyla kaydedildi!']);
        }
    });

    // ÇIKIŞ
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});