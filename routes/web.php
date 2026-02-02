<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\FinansController;
use Spatie\Permission\Middleware\RoleMiddleware;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;


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
            $seconds = (int) $request->seconds;
    
            // Haftanın Pazartesi'si
            $thisWeekStart = now()->startOfWeek(); // Laravel default: Pazartesi
    
            // Eğer daha önce hiç set edilmemişse veya eski haftaysa, haftayı sıfırla
            if (
                !$user->weekly_work_hours_week_start ||
                \Illuminate\Support\Carbon::parse($user->weekly_work_hours_week_start)->lt($thisWeekStart)
            ) {
                $user->weekly_work_hours = 0;
                $user->weekly_work_hours_week_start = $thisWeekStart;
            }
    
            // Bu haftanın süresine ekle
            $user->weekly_work_hours += $seconds;
            $user->save();
    
            return response()->json(['message' => 'Süre başarıyla kaydedildi!']);
        }
    
        return response()->json(['message' => 'Yetkisiz'], 401);
    });

    // ÇIKIŞ
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});


// --- HATALI KISIMLARI SİLİN VE BUNU EKLEYİN ---

// 1. Finans Görevlisi Rotaları (SADECE Finansal işler kalsın)
Route::middleware(['auth', RoleMiddleware::class . ':Finans Gorevlisi'])->group(function () {
    Route::get('/finans', [FinansController::class, 'index'])->name('finans.index');
    Route::get('/finans/create', [FinansController::class, 'create'])->name('finans.create');
    Route::post('/finans', [FinansController::class, 'store'])->name('finans.store');
    Route::get('/finans/{user}', [FinansController::class, 'show'])->name('finans.show');
});

// 2. Şirket Yöneticisi Rotaları (Kullanıcı Yönetimi)
Route::middleware(['auth', RoleMiddleware::class . ':Sirket Yoneticisi'])->group(function () {
    // Liste sayfası
    Route::get('/users', [ProfileController::class, 'index'])->name('users.index');
    
    // Yeni Kullanıcı Ekleme
    Route::get('/yenikullanici', [ProfileController::class, 'create'])->name('yenikullanici.create');
    Route::post('/yenikullanici', [ProfileController::class, 'store'])->name('yenikullanici.store');
    
    // Düzenleme ve Güncelleme (ID kullanarak)
    Route::get('/users/edit/{id}', [ProfileController::class, 'editUser'])->name('users.edit');
    Route::put('/users/update/{id}', [ProfileController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [ProfileController::class, 'destroy'])->name('users.destroy');
});

// 3. Profil (Kendi hesabını düzenleme)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');