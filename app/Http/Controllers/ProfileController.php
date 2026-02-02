<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class ProfileController extends Controller
{
    use AuthorizesRequests; // authorize() metodunu kullanabilmek için

    /**
     * Şirkete ait tüm kullanıcıları listeler.
     */
    public function index()
    {
        // UserPolicy@viewAny metoduna bakar
        Gate::authorize('viewAny', User::class);

        $user = Auth::user();
        
        // Sadece giriş yapan yöneticinin şirketindeki kullanıcıları getirir
        $users = User::where('company_id', $user->company_id)->get();
        
        return view('users.index', compact('users', 'user'));
    }

    /**
     * Yeni kullanıcı oluşturma formu.
     */
    public function create()
    {
        Gate::authorize('create', User::class);
        
        $user = Auth::user();
        $roles = Role::pluck('name'); 

        return view('create', compact('roles', 'user'));
    }

    /**
     * Yeni kullanıcıyı kaydeder.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', User::class);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'nullable|string|exists:roles,name',
        ]);

        $validated['password'] = Hash::make($request->password);
        $validated['company_id'] = Auth::user()->company_id;

        $newUser = User::create($validated);

        if ($request->filled('role')) {
            $newUser->assignRole($request->role);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }

    /**
     * Kullanıcı düzenleme sayfası.
     */
    public function editUser($id) 
    {
        $userToEdit = User::findOrFail($id);

        // UserPolicy@update metoduna bakar (Parametre olarak modeli gönderiyoruz)
        Gate::authorize('update', $userToEdit);

        $user = Auth::user(); // Sidebar/Layout için
        $roles = Role::pluck('name'); 

        return view('users.edit', compact('userToEdit', 'user', 'roles'));
    }

    /**
     * Kullanıcıyı günceller.
     */
    public function updateUser(Request $request, $id)
    {
        $userToUpdate = User::findOrFail($id);

        // Yetki kontrolü
        Gate::authorize('update', $userToUpdate);

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userToUpdate->id,
            'role'  => 'nullable|string|exists:roles,name',
            'password' => 'nullable|string|min:8',
        ]);

        $userToUpdate->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($request->filled('password')) {
            $userToUpdate->update(['password' => Hash::make($request->password)]);
        }

        if ($request->filled('role')) {
            $userToUpdate->syncRoles($request->role);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'Kullanıcı bilgileri güncellendi.');
    }

    /**
     * Kullanıcıyı siler.
     */
    public function destroy($id)
    {
        $userToDelete = User::findOrFail($id);

        // Gate: UserPolicy@delete metoduna bakar
        // Kural: Yönetici olacak, aynı şirkette olacak ve KENDİSİNİ silemeyecek
        $this->authorize('delete', $userToDelete);

        $userToDelete->delete();

        return back()->with('success', 'Kullanıcı başarıyla silindi.');
    }
}