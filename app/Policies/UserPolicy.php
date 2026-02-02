<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{

    public function create(User $user): bool
    {
        // Sadece 'Sirket Yoneticisi' olanlar yeni kullanıcı ekleyebilir
        return $user->hasRole('Sirket Yoneticisi');
    }
    /**
     * Kimlerin kullanıcı listesini görebileceğini belirler.
     */
    public function viewAny(User $user): bool
    {
        // Sadece şirket yöneticileri listeyi görebilsin
        return $user->hasRole('Sirket Yoneticisi');
    }

    /**
     * Belirli bir kullanıcıyı görüntüleme yetkisi.
     */
    public function view(User $user, User $model): bool
    {
        // Yöneticiyse veya kendi profiliyse görebilir
        return $user->hasRole('Sirket Yoneticisi') || $user->id === $model->id;
    }

    /**
     * Düzenleme (Update) yetkisi - 403 hatasını çözen kısım burası.
     */
    public function update(User $user, User $model): bool
    {
        // 1. Şirket yöneticisi mi? 
        // 2. Ve aynı şirketteler mi? (Güvenlik için)
        return $user->hasRole('Sirket Yoneticisi') && $user->company_id === $model->company_id;
    }

    /**
     * Silme yetkisi.
     */
    public function delete(User $user, User $model): bool
    {
        // 1. İşlemi yapan Şirket Yöneticisi mi?
        // 2. Düzenlenen kişiyle aynı şirkette mi?
        // 3. Silinen kişi, işlemi yapan kişinin kendisi DEĞİL mi?
        return $user->hasRole('Sirket Yoneticisi') && 
            $user->company_id === $model->company_id && 
            $user->id !== $model->id;
    }
}