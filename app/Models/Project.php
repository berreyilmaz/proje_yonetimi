<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use App\Models\FinancialTransaction;


class Project extends Model
{
    use HasRoles;
    protected $fillable = ['company_id', 'title', 'description', 'status', 'start_date', 'end_date','progress','project_manager_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function userRoles()
    {
        return $this->hasMany(ProjectUserRole::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user_roles', 'project_id', 'user_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }



    public function updateProgress()
    {
        // Proje ID'sine göre görevleri direkt veritabanından say
        $total = \App\Models\Task::where('project_id', $this->id)->count();

        if ($total == 0) {
            $this->progress = 0;
            $this->save();
            return 0;
        }

        // Durumu 'tamamlandi' olanları direkt veritabanından say
        $completed = \App\Models\Task::where('project_id', $this->id)
            ->where('status', 'tamamlandi')
            ->count();

        $percentage = round(($completed / $total) * 100);

        // Veritabanına kaydet
        $this->progress = $percentage;
        $this->save();

        return $percentage;
    }

    public function financialTransactions()
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    // Projenin anlık kar/zarar durumunu hesaplayan metod
    public function getFinancialBalanceAttribute()
    {
        $income = $this->financialTransactions()->where('type', 'income')->sum('amount');
        $expense = $this->financialTransactions()->where('type', 'expense')->sum('amount');
        return $income - $expense;
    }
}
