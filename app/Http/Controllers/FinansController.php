<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Finans;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;

class FinansController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $companyId = $user->company_id;

        $employees = User::where('company_id', $companyId)
            ->with(['roles','tasksAssigned','finansPayments'])
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

        return view('finans.index', compact('employees'));
    }


    public function show(\App\Models\User $user)
    {
        $auth = \Illuminate\Support\Facades\Auth::user();
        // Güvenlik: aynı şirkette olmayanlara izin verme
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

    public function create(Request $request)
    {
        $companyId = Auth::user()->company_id;
        $users = User::where('company_id', $companyId)->get();
        $roles = Role::pluck('name'); // rol isimleri
        $selectedUser = $request->query('user_id') ? $users->firstWhere('id', $request->query('user_id')) : null;
        return view('finans.create', compact('users', 'selectedUser', 'roles'));
    }

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

    public function editUser(User $user)
    {
        if (Auth::user()->company_id !== $user->company_id) {
            abort(403);
        }

        // Klasör yolunu finans.edit yaptık
        return view('finans.edit', compact('user'));
    }

    /**
     * Veritabanında güncelleme yapar
     */
    public function updateUser(Request $request, User $user)
    {
        $data = $request->validate([
            'hourly_rate' => 'nullable|numeric|min:0',
            'weekly_work_hours' => 'nullable|integer',
        ]);

        $user->update($data);

        return redirect()->route('finans.index')->with('success', 'Finansal veriler güncellendi.');
    }
    
}