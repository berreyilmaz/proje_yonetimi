<?php
namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Project $project)
    {
        // 1. Projelerden Beslenme (Tüm projeleri alıyoruz)
        $projects = Project::where('company_id', auth()->user()->company_id)->get();

        // 2. Görevlerden Beslenme
        $tasksCount = \App\Models\Task::whereIn('project_id', $projects->pluck('id'))->count();

        // 3. Finanstan Beslenme (Örnek bütçe toplamı)
        $totalRevenue = $projects->sum('budget');

        // 4. Operasyondan Beslenme (Yapay bir verimlilik skoru örneği)
        // Gerçek verin varsa buraya o mantığı yazabilirsin
        $efficiencyScore = $projects->avg('progress') ?? 0;

        return view('report.index', compact(
            'project',
            'projects',
            'tasksCount',
            'totalRevenue',
            'efficiencyScore'
        ));
    }
}