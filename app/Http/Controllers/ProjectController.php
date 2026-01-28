<?php

namespace App\Http\Controllers;

use App\Models\Project; // Model'i eklemeyi unutma
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // 1. Bunu ekle
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;


class ProjectController extends Controller
{
    use AuthorizesRequests; // 2. Bunu buraya dahil et


    public function index()
    {
        // 1. Kullanıcı kontrolü
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // 2. Yetki Kontrolü (İsteğe bağlı)
        // Eğer paneli sadece belirli yetkisi olanlar görecekse aşağıdaki satırı açabilirsin:
        // $this->authorize('proje görüntüle');

        $companyId = $user->company_id;

        // 3. İstatistikler (Sadece kullanıcının kendi şirketine ait veriler)
        $completedProjectsCount = Project::where('company_id', $companyId)
            ->where('status', 'tamamlandi')
            ->count();

        $continuingProjectsCount = Project::where('company_id', $companyId)
            ->where('status', 'devam_ediyor')
            ->count();

        // 4. Devam Eden Projeler (Dashboard'daki görev kartları için)
        $continuingProjects = Project::where('company_id', $companyId)
            ->where('status', 'devam_ediyor')
            ->get();

        // 5. Ekip Üyeleri (Sadece aynı şirkettekiler)
        $teamMembers = User::where('company_id', $companyId)
            ->take(5) // Blade içinde take(2) yapıyorduk, güvenli olması için 5 tane çekiyoruz
            ->get();

        $teamCount = User::where('company_id', $companyId)->count();

        // 6. Blade'in beklediği ek değişkenler (Hata veren kısımlar)
        $personalHours = "38s 15dk"; 
        $currentDate = now();
        $startOfWeek = now()->startOfWeek();

        // 7. Verileri View'a gönder
        return view('index', compact(
            'continuingProjects',
            'completedProjectsCount',
            'continuingProjectsCount',
            'teamMembers',
            'teamCount',
            'personalHours',
            'currentDate',
            'startOfWeek'
        ));
    }

    public function destroy(Project $project)
    {
        Gate::authorize('delete', Project::class); // Gate üzerinden kontrol
        $project->delete();
        return back();
    }

    public function list()
    {
        Gate::authorize('viewAny', Project::class);
        $user = Auth::user();
    
        // Güvenlik: Giriş yapmamışsa engelle
        if (!$user) {
            return redirect()->route('login');
        }

        // Sadece kullanıcının şirketine ait projeleri, en yeniye göre çekiyoruz
        $projects = Project::where('company_id', $user->company_id)
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        return view('projects.index', compact('projects', 'user'));
    }

    public function create()
    {
        Gate::authorize('create', Project::class);
        $user = Auth::user(); // Sidebar için
        return view('projects.create', compact('user'));
    }

    public function store(Request $request)
    {
            // 1️⃣ Yetki kontrolü
        Gate::authorize('create', Project::class);

        // 2️⃣ Validasyon
        $validated = $request->validate([
            'title' => 'required|max:255',
            'status' => 'required',
            'progress' => 'required|integer|min:0|max:100',
            'end_date' => 'nullable|date',
        ]);

        // 3️⃣ Status map
        $statusMap = [
            'continuing' => 'devam_ediyor',
            'completed'  => 'tamamlandi',
        ];

        // 4️⃣ Güvenli alanlar
        $validated['status'] = $statusMap[$request->status] ?? 'devam_ediyor';
        $validated['company_id'] = Auth::user()->company_id; // 🔥 KRİTİK
        $validated['start_date'] = now();

        // 5️⃣ Kayıt
        Project::create($validated);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Proje başarıyla oluşturuldu');
    }

    public function edit(Project $project)
    {
        Gate::authorize('update', $project);
        $user = Auth::user(); // Sidebar ve header için
        return view('projects.edit', compact('project', 'user'));
    }

    public function update(Request $request, Project $project)
    {
        Gate::authorize('update', $project);
        $validated = $request->validate([
            'title' => 'required|max:255',
            'status' => 'required',
            'progress' => 'required|integer|min:0|max:100',
            'end_date' => 'nullable|date',
        ]);

        // Veritabanı formatına dönüştürme (İstersen Blade'den de yapabilirsin)
        $statusMap = [
            'continuing' => 'devam_ediyor',
            'completed'  => 'tamamlandi'
        ];
        $validated['status'] = $statusMap[$request->status] ?? $request->status;

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Proje başarıyla güncellendi.');
    }

        public function show(Project $project)
    {
        Gate::authorize('view', $project);
        return view('projects.show', compact('project'));
    }
}