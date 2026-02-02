<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Company;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function projectRoles()
    {
        return $this->hasMany(ProjectUserRole::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user_roles')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function projectRole(\App\Models\Project $project): ?string
    {
        return \App\Models\ProjectUserRole::where('project_id', $project->id)
            ->where('user_id', $this->id)
            ->value('role');
    }

    public function hasProjectAbility(\App\Models\Project $project, string $ability): bool
    {
        $role = $this->projectRole($project);
        if (!$role) return false;

        $allowedRoles = config("project_roles.abilities.$ability", []);
        return in_array($role, $allowedRoles, true);
    }

    public function tasksAssigned()
    {
        return $this->hasMany(\App\Models\Task::class, 'assigned_to');
    }

    public function finansPayments()
    {
        return $this->hasMany(\App\Models\Finans::class);
    }
}
