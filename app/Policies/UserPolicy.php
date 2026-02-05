<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Kimlerin kullanıcı listesini görebileceğini belirler.
     */
    public function viewAny(User $user): bool
    {
        // Yönetici VEYA Finans Görevlisi listeyi görebilmeli
        return $user->hasAnyRole(['Sirket Yoneticisi', 'Finans Gorevlisi']);
    }

    /**
     * Yeni kullanıcı ekleme yetkisi.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Sirket Yoneticisi');
    }

    /**
     * Belirli bir kullanıcıyı görüntüleme yetkisi.
     */
    public function view(User $user, User $model): bool
    {
        // Kendi profiliyse VEYA (Yönetici/Finansçıysa ve aynı şirkettelerse)
        return $user->id === $model->id || 
               ($user->hasAnyRole(['Sirket Yoneticisi', 'Finans Gorevlisi']) && $user->company_id === $model->company_id);
    }

    /**
     * Düzenleme (Update) yetkisi.
     */
    public function update(User $user, User $model): bool
    {
        // hasAnyRole kullanarak her iki role de izin veriyoruz
        // Aynı zamanda aynı şirkette olduklarından emin oluyoruz
        return $user->hasAnyRole(['Sirket Yoneticisi', 'Finans Gorevlisi']) && 
               $user->company_id === $model->company_id;
    }

    /**
     * Silme yetkisi.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('Sirket Yoneticisi') && 
               $user->company_id === $model->company_id && 
               $user->id !== $model->id;
    }
}