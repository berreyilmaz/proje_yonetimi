<?php
namespace App\Http\Controllers;

use App\Models\Operation;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperationController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Sadece kullanıcının şirketine ait projelerin operasyonlarını getir
        $operations = Operation::whereHas('project', function($query) use ($user) {
            $query->where('company_id', $user->company_id);
        })->with(['project', 'requester'])->latest()->paginate(10);

        // İstatistikler
        $stats = [
            'pending' => Operation::whereHas('project', function($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->where('status', 'pending')->count(),
            
            'total_budget' => Operation::whereHas('project', function($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->where('type', 'budget')->where('status', 'approved')->sum('impact_value')
        ];

        return view('operations.index', compact('operations', 'stats', 'user'));
    }


    // Oluşturma Sayfası (Formun Açılması)
    public function create()
    {
        // Sadece kullanıcının kendi şirketine ait projeleri seçebilmesi için
        $projects = Project::where('company_id', Auth::user()->company_id)->get();
        
        return view('operations.create', compact('projects'));
    }

    // Veritabanına Kaydetme
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'type' => 'required|in:time,budget,resource,process',
            'impact_value' => 'required|numeric',
            'description' => 'required|string|min:10',
        ]);

        Operation::create([
            'project_id' => $validated['project_id'],
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'impact_value' => $validated['impact_value'],
            'description' => $validated['description'],
            'status' => 'pending', // Akademik kural: Onaylanmadan aktif olmaz
        ]);

        return redirect()->route('operations.index')
                         ->with('success', 'Operasyon talebi başarıyla kayıt altına alındı ve onaya gönderildi.');
    }

    public function approve($id)
    {
        $operation = \App\Models\Operation::findOrFail($id);
        
        // 1. Önce operasyonun durumunu güncelle (Burası önemli!)
        $operation->status = 'approved';
        $operation->approved_by = Auth::id();
        $operation->save();

        // 2. Proje üzerindeki etkiyi uygula
        $project = $operation->project;
        
        if ($operation->type === 'budget') {
            // Tablona eklediğin sütun ismi tam olarak 'budget' olmalı
            $project->budget += $operation->impact_value;
        } elseif ($operation->type === 'time') {
            $project->end_date = \Illuminate\Support\Carbon::parse($project->end_date)
                ->addDays($operation->impact_value);
        }
        
        $project->save(); // Hata buradaydı, artık budget sütunu olduğu için geçecek.

        return redirect()->route('operations.index')->with('success', 'Operasyon onaylandı!');
    }

    public function reject($id)
    {
        $operation = \App\Models\Operation::findOrFail($id);
        
        // Durumu 'rejected' yap ve işlemi yapanı kaydet
        $operation->status = 'rejected';
        $operation->approved_by = Auth::id();
        $operation->save();

        return back()->with('error', 'Operasyon reddedildi!');
    }
}
