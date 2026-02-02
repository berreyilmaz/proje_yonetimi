<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

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
        return $this->belongsToMany(User::class, 'project_user_roles')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(\App\Models\Task::class);
    }
}
