<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        // Her giriş yapmış kullanıcı görevleri görebilsin (şirket filtresi zaten controller'da)
        return true;
    }

    public function view(User $user, Task $task): bool
    {
        // Aynı şirketteki görevleri görebilsin
        return $user->company_id === $task->company_id;
    }

    public function create(User $user): bool
    {
        // Sadece Operasyon Yoneticisi görev ekleyebilsin
        return $user->hasRole('Operasyon Yoneticisi');
    }

    public function update(User $user, Task $task): bool
    {
        // 1) Operasyon Yoneticisi ise: kendi şirketindeki tüm görevleri güncelleyebilir
        if ($user->hasRole('Operasyon Yoneticisi') && $user->company_id === $task->company_id) {
            return true;
        }

        // 2) Görev sorumlusu ise: kendi şirketindeki ve kendisine atanmış görevi güncelleyebilir
        return $user->company_id === $task->company_id
            && $task->assigned_to === $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        // Sadece Operasyon Yoneticisi, kendi şirketindeki görevi silebilsin
        return $user->hasRole('Operasyon Yoneticisi')
            && $user->company_id === $task->company_id;
    }
}