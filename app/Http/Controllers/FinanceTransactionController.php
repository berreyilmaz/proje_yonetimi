<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;



class FinanceTransactionController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Sadece dahil olduğu projelerin finansal verileri (Az önceki yetki mantığıyla)
        $projects = Project::where('company_id', $user->company_id)
            ->whereHas('users', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with('financialTransactions')
            ->get();

        return view('finance.index', compact('projects'));
    }
}
