<?php

namespace App\Http\Controllers;

use App\Models\Project; // Model'i eklemeyi unutma
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // 1. Bunu ekle
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Traits\HasRoles;


class ProjectController extends Controller
{
    use AuthorizesRequests; // 2. Bunu buraya dahil et


    public function index()
{
    /** @var \App\Models\User $user */
    $user = Auth::user();
    if (!$user) return redirect()->route('login');

    $companyId = $user->company_id;
    
    // Küçük/büyük harf duyarlılığını kaldırmak için role isimlerini kontrol et
    $isRestricted = !$user->hasAnyRole(['Admin', 'admin', 'Operasyon Yoneticisi', 'operasyon_yoneticisi']);

    // 1. Kullanıcının dahil olduğu projelerin ID'lerini önceden alalım (En garantisi budur)
    $myProjectIds = \App\Models\ProjectUserRole::where('user_id', $user->id)
        ->pluck('project_id')
        ->toArray();

    // 2. Filtreleme Fonksiyonu
    $applyRestrictions = function($query) use ($companyId, $isRestricted, $user, $myProjectIds) {
        $query->where('company_id', $companyId);
        
        if ($isRestricted) {
            $query->where(function($q) use ($user, $myProjectIds) {
                $q->where('project_manager_id', $user->id) // Yönetici olduğu projeler
                  ->orWhereIn('id', $myProjectIds);        // Üye olduğu projeler
            });
        }
        return $query;
    };

    // 3. Verileri Çek
    $completedProjectsCount = $applyRestrictions(Project::query())->where('status', 'tamamlandi')->count();
    $continuingProjectsCount = $applyRestrictions(Project::query())->where('status', 'devam_ediyor')->count();
    $continuingProjects = $applyRestrictions(Project::query())->where('status', 'devam_ediyor')->latest()->take(2)->get();

    // Diğer veriler aynı...
    $teamMembers = User::where('company_id', $companyId)->take(5)->get();
    $teamCount = User::where('company_id', $companyId)->count();
    $personalHours = intdiv((int)$user->weekly_work_hours, 3600) . ' sa ' . intdiv(((int)$user->weekly_work_hours % 3600), 60) . ' dk';
    $currentDate = now();
    $startOfWeek = now()->startOfWeek();

    return view('index', compact('continuingProjects','completedProjectsCount','continuingProjectsCount','teamMembers','teamCount','personalHours','currentDate','startOfWeek'));
}

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project); 

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Proje silindi.');
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
            'members' => 'nullable|array',
        ]);

        $statusMap = [
            'continuing' => 'devam_ediyor',
            'completed'  => 'tamamlandi',
        ];

        $validated['status'] = $statusMap[$request->status] ?? 'devam_ediyor';
        $validated['company_id'] = Auth::user()->company_id;
        $validated['start_date'] = now();
        $validated['project_manager_id'] = $request->project_manager_id;

        // --- KRİTİK DÜZELTME BAŞLANGICI ---
        // 'members' verisini ana tabloya kaydetmeye çalışmasın diye diziden çıkarıyoruz
        $selectedMembers = $validated['members'] ?? []; // Üyeleri bir değişkene al
        unset($validated['members']); // Validated dizisinden temizle (Hata veren kısım burasıydı)
        // --- KRİTİK DÜZELTME BİTİŞİ ---

        // SADECE BİR KEZ OLUŞTUR
        $project = Project::create($validated);

        // SEÇİLEN ÜYELERİ EKLE
        if (!empty($selectedMembers)) {
            foreach ($selectedMembers as $userId) {
                \App\Models\ProjectUserRole::updateOrCreate(
                    [
                        'project_id' => $project->id,
                        'user_id'    => $userId,
                    ],
                    [
                        'role'       => 'member',
                    ]
                );
            }
        }

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
            ->with('success', 'Proje ve ekip başarıyla oluşturuldu');
    }

    public function edit(Project $project)
    {
        Gate::authorize('update', $project);
        $user = Auth::user();

        $employees = User::where('company_id', $user->company_id)->get();

        // BOZMADAN DÜZELTİLEN KISIM: 
        // Projenin üyelerini direkt olarak senin kullandığın ProjectUserRole tablosundan çekiyoruz.
        // Böylece modeldeki ilişki hatalı olsa bile üyeler kesin gelir.
        $currentMembers = \App\Models\ProjectUserRole::where('project_id', $project->id)
            ->pluck('user_id')
            ->toArray();

        return view('projects.edit', compact('project', 'user', 'employees', 'currentMembers'));
    }
    public function update(Request $request, Project $project)
    {
        // 1. Yetki Kontrolü
        Gate::authorize('update', $project);

        // 2. Doğrulama (Progress alanını nullable yaptık ki hata vermesin)
        $validated = $request->validate([
            'title'   => 'required|max:255',
            'status'  => 'required',
            'progress'=> 'nullable|integer|min:0|max:100',
            'end_date'=> 'nullable|date',
            'project_manager_id' => 'nullable|exists:users,id',
            'members' => 'nullable|array',
        ]);

        // 3. Durum Dönüştürme
        $statusMap = [
            'continuing' => 'devam_ediyor',
            'completed'  => 'tamamlandi'
        ];
        
        // Verileri hazırla (members'ı ana tablodan ayır)
        $data = $validated;
        $memberIds = $request->input('members', []);
        $managerId = $request->project_manager_id;
        unset($data['members']); 
        
        $data['status'] = $statusMap[$request->status] ?? $request->status;

        // 4. Proje Ana Tablosunu Güncelle
        $project->update($data);

        // 5. Roller Tablosundaki Eski Kayıtları Sil (Temiz bir sayfa)
        \App\Models\ProjectUserRole::where('project_id', $project->id)->delete();

        // 6. Üyeleri Kaydet (Duplicate Hatasını Önleyen Mantık)
        foreach ($memberIds as $userId) {
            // EĞER BU KULLANICI AYNI ZAMANDA YÖNETİCİYSE ÜYE OLARAK EKLEME (Aşağıda Manager olarak eklenecek)
            if ($userId == $managerId) {
                continue;
            }

            \App\Models\ProjectUserRole::create([
                'project_id' => $project->id,
                'user_id'    => $userId,
                'role'       => 'member'
            ]);
        }

        // 7. Yöneticiyi Kaydet
        if ($managerId) {
            \App\Models\ProjectUserRole::create([
                'project_id' => $project->id,
                'user_id'    => $managerId,
                'role'       => 'manager'
            ]);
        }

        return redirect()->route('projects.index')->with('success', 'Proje ve ekip başarıyla güncellendi.');
    }

        public function show(Project $project)
    {
        Gate::authorize('view', $project);
        return view('projects.show', compact('project'));
    }
}