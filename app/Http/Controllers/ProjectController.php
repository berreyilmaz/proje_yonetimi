<?php

namespace App\Http\Controllers;

use App\Models\Project; // Model'i eklemeyi unutma
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // 1. Bunu ekle
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;


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
            ->latest()   // created_at desc
            ->take(2)
            ->get();

        // 5. Ekip Üyeleri (Sadece aynı şirkettekiler)
        $teamMembers = User::where('company_id', $companyId)
            ->take(5) // Blade içinde take(2) yapıyorduk, güvenli olması için 5 tane çekiyoruz
            ->get();

        $teamCount = User::where('company_id', $companyId)->count();

        // 6. Blade'in beklediği ek değişkenler (Hata veren kısımlar)
        // Kullanıcının bu haftaki toplam saniyesi
        $seconds = (int) $user->weekly_work_hours;

        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);

        // Örn: "5 sa 30 dk"
        $personalHours = $hours . ' sa ' . $minutes . ' dk'; 
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
            'startOfWeek',
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

        // Aynı şirketteki proje yöneticileri
        $employees = User::where('company_id', $user->company_id)->get();

        return view('projects.create', compact('user', 'employees'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Project::class);

        $validated = $request->validate([
            'title'   => 'required|max:255',
            'status'  => 'required',
            'progress'=> 'nullable|integer|min:0|max:100',
            'end_date'=> 'nullable|date',
            'project_manager_id' => 'nullable|exists:users,id',
        ]);

        $statusMap = [
            'continuing' => 'devam_ediyor',
            'completed'  => 'tamamlandi',
        ];

        $validated['status'] = $statusMap[$request->status] ?? 'devam_ediyor';
        $validated['company_id'] = Auth::user()->company_id;
        $validated['start_date'] = now();
        $validated['project_manager_id'] = $request->project_manager_id;

        // SADECE BİR KEZ OLUŞTUR
        $project = Project::create($validated);

        // Proje bazlı manager rolü ata
        if ($request->filled('project_manager_id')) {
            \App\Models\ProjectUserRole::updateOrCreate(
                [
                    'project_id' => $project->id,
                    'user_id'    => $request->project_manager_id,
                ],
                [
                    'role'       => 'manager',
                ]
            );
        }

        return redirect()
            ->route('projects.index')
            ->with('success', 'Proje başarıyla oluşturuldu');
    }

    public function edit(Project $project)
    {
        Gate::authorize('update', $project);
        $user = Auth::user();

        $employees = User::where('company_id', $user->company_id)->get();

        return view('projects.edit', compact('project', 'user', 'employees'));
    }
    public function update(Request $request, Project $project)
    {
        Gate::authorize('update', $project);
        $validated = $request->validate([
            'title'   => 'required|max:255',
            'status'  => 'required',
            'progress'=> 'required|integer|min:0|max:100',
            'end_date'=> 'nullable|date',
            'project_manager_id' => 'nullable|exists:users,id',
        ]);
        
        $statusMap = [
            'continuing' => 'devam_ediyor',
            'completed'  => 'tamamlandi'
        ];
        $validated['status'] = $statusMap[$request->status] ?? $request->status;
        $validated['project_manager_id'] = $request->project_manager_id;
        
        $project->update($validated);

        \App\Models\ProjectUserRole::where('project_id', $project->id)
        ->where('role', 'manager')
        ->delete();

        if ($request->filled('project_manager_id')) {
            \App\Models\ProjectUserRole::updateOrCreate(
                [
                    'project_id' => $project->id,
                    'user_id'    => $request->project_manager_id,
                ],
                [
                    'role'       => 'manager',
                ]
            );
        }
        return redirect()->route('projects.index')->with('success', 'Proje başarıyla güncellendi.');
    }

        public function show(Project $project)
    {
        Gate::authorize('view', $project);
        return view('projects.show', compact('project'));
    }
}