<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Policy: viewAny (şu an true dönüyor)
        $this->authorize('viewAny', Task::class);

        // Şirket bazlı filtre
        $tasks = Task::where('company_id', $user->company_id);

        // Eğer rolü sadece 'Employee' ise sadece kendi görevlerini görsün
        if ($user->hasRole('Employee')) {
            $tasks->where('assigned_to', $user->id);
        }

        $tasks = $tasks->latest()->get();

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        // Sadece Operasyon Yoneticisi (TaskPolicy@create)
        $this->authorize('create', Task::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Sadece kullanıcının bağlı olduğu şirketin projelerini ve çalışanlarını getir
        $projects = Project::where('company_id', $user->company_id)->get();
        $employees = User::where('company_id', $user->company_id)->get();

        return view('tasks.create', compact('projects', 'employees'));
    }

    public function store(Request $request)
    {
        // Sadece Operasyon Yoneticisi (TaskPolicy@create)
        $this->authorize('create', Task::class);

        // Formdan gelen verileri doğrula
        $request->validate([
            'title'       => 'required|max:255',
            'assigned_to' => 'required|exists:users,id',
            'status'      => 'required',
        ]);

        // Kaydı oluştur
        Task::create([
            'title'       => $request->title,
            'project_id'  => $request->project_id,
            'assigned_to' => $request->assigned_to,
            'status'      => $request->status,
            'company_id'  => Auth::user()->company_id, // Kullanıcının şirketini otomatik ekle
        ]);

        return redirect()->route('tasks.index')->with('success', 'Görev başarıyla eklendi!');
    }

    // Görev düzenleme formu
    public function edit(Task $task)
    {
        // Sadece Operasyon Yoneticisi + aynı şirket (TaskPolicy@update)
        $this->authorize('update', $task);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $projects  = Project::where('company_id', $user->company_id)->get();
        $employees = User::where('company_id', $user->company_id)->get();

        return view('tasks.edit', compact('task', 'projects', 'employees'));
    }

    // Görev güncelleme
    public function update(Request $request, Task $task)
    {
        // Sadece Operasyon Yoneticisi + aynı şirket (TaskPolicy@update)
        $this->authorize('update', $task);

        $request->validate([
            'title'       => 'required|max:255',
            'assigned_to' => 'required|exists:users,id',
            'status'      => 'required',
        ]);

        $task->update([
            'title'       => $request->title,
            'project_id'  => $request->project_id,
            'assigned_to' => $request->assigned_to,
            'status'      => $request->status,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Görev başarıyla güncellendi!');
    }

    // Görev silme
    public function destroy(Task $task)
    {
        // Sadece Operasyon Yoneticisi + aynı şirket (TaskPolicy@delete)
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Görev başarıyla silindi!');
    }
}