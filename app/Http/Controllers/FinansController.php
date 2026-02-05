<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Finans;
use App\Models\Project;
use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FinansController extends Controller
{
    use AuthorizesRequests;

    /**
     * Şirkete ait çalışanları ve projeleri listeler.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $companyId = $user->company_id;

        $employees = User::where('company_id', $companyId)
            ->with(['roles', 'tasksAssigned', 'finansPayments'])
            ->withCount('tasksAssigned')
            ->get()
            ->map(function ($u) {
                $sec = (int) ($u->weekly_work_hours ?? 0);
                $hrs = intdiv($sec, 3600);
                $mins = intdiv($sec % 3600, 60);
                $u->weekly_hours_text = "{$hrs} sa {$mins} dk";
                $u->tasks_count = $u->tasks_assigned_count ?? $u->tasksAssigned->count();
                $u->total_paid = (float) $u->finansPayments->sum('amount');
                return $u;
            });

        $projects = Project::where('company_id', $companyId)
            ->with(['financialTransactions'])
            ->get();

        return view('finans.index', compact('employees', 'projects'));
    }

    /**
     * Bir çalışanın detaylı finansal dökümünü (bordro) gösterir.
     */
    public function show(User $user)
    {
        $auth = Auth::user();
        // Güvenlik: Aynı şirkette olmayanlara izin verme
        if ($auth->company_id !== $user->company_id) {
            abort(403);
        }

        $tasks = $user->tasksAssigned()->latest()->get();
        $payments = $user->finansPayments()->latest()->get();

        $sec = (int) ($user->weekly_work_hours ?? 0);
        $hrs = intdiv($sec, 3600);
        $mins = intdiv($sec % 3600, 60);
        $weekly_hours_text = "{$hrs} sa {$mins} dk";

        $total_paid = (float) $payments->sum('amount');

        return view('finans.show', compact('user', 'tasks', 'payments', 'weekly_hours_text', 'total_paid'));
    }

    /**
     * Finansal işlem (ödeme/avans) ekleme formu.
     */
    public function create(Request $request)
    {
        $companyId = Auth::user()->company_id;
        $users = User::where('company_id', $companyId)->get();
        $roles = Role::pluck('name'); 
        $selectedUser = $request->query('user_id') ? $users->firstWhere('id', $request->query('user_id')) : null;
        
        return view('finans.create', compact('users', 'selectedUser', 'roles'));
    }

    /**
     * Yeni bir finansal işlemi kaydeder.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'     => 'required|exists:users,id',
            'amount'      => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'date'        => 'nullable|date',
        ]);

        $data['company_id'] = Auth::user()->company_id;
        Finans::create($data);

        return redirect()->route('finans.show', $data['user_id'])->with('success', 'Finans kaydı eklendi.');
    }

    /**
     * Kullanıcının finansal profil düzenleme sayfasını açar.
     */
    public function editUser(User $user)
    {
        // Şirket kontrolü
        if (Auth::user()->company_id !== $user->company_id) {
            abort(403);
        }

        // Policy üzerinden yetki kontrolü
        $this->authorize('update', $user);

        return view('finans.edit', compact('user'));
    }

    /**
     * Kullanıcının maaş, mesai ve saatlik ücret bilgilerini günceller.
     */
    public function updateUser(Request $request, User $user)
    {
        // Şirket kontrolü
        if (Auth::user()->company_id !== $user->company_id) {
            abort(403);
        }

        $this->authorize('update', $user);

        $data = $request->validate([
            'base_salary'         => 'nullable|numeric|min:0',
            'overtime_rate'       => 'nullable|numeric|min:0',
            'monthly_limit_hours' => 'nullable|integer|min:0',
            'weekly_work_hours'   => 'nullable|integer',
            'hourly_rate'         => 'nullable|numeric|min:0', // Mevcut alan korunuyor
        ]);

        $user->update($data);

        return redirect()->route('finans.index')->with('success', 'Personel finansal profili güncellendi.');
    }

    /**
     * Proje bazlı finansal hareket detayları.
     */
    public function projectDetails(Project $project)
    {
        if (Auth::user()->company_id !== $project->company_id) {
            abort(403);
        }

        $transactions = $project->financialTransactions()
            ->with(['referencable'])
            ->orderBy('transaction_date', 'desc')
            ->get();

        return view('finans.project_details', compact('project', 'transactions'));
    }

    /**
     * Proje bütçesini günceller.
     */
    public function updateBatchBudget(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'budget'     => 'required|numeric|min:0',
        ]);

        $project = Project::find($request->project_id);
        
        if (Auth::user()->company_id !== $project->company_id) {
            abort(403);
        }

        $project->update([
            'budget' => $request->budget
        ]);

        return redirect()->back()->with('success', 'Bütçe başarıyla güncellendi.');
    }
}