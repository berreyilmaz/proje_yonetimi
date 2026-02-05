<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Providers\AuthServiceProvider;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('proje.goruntule');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        return $user->company_id === $project->company_id
            && $user->hasProjectAbility($project, 'project.view');    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('proje.ekle');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        // Global yetkisi olanlar veya o projede manager rolünde olanlar düzenleyebilir
        $isProjectManager = \App\Models\ProjectUserRole::where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->where('role', 'manager')
            ->exists();

            return ($user->hasRole(['Takim Lideri', 'Sirket Yoneticisi'])) 
                && $user->company_id === $project->company_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->can('proje.sil') && $user->company_id === $project->company_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return false;
    }

    public function assignStaff(User $user, Project $project): bool
    {
        return $user->can('projeye.personel.ata') && $user->company_id === $project->company_id;
    }



}
