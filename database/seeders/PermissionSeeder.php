<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Önbelleği temizle (Spatie için önemlidir)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. İzinleri Oluştur
        $permissions = [
            'proje görüntüle',
            'proje oluştur',
            'proje düzenle',
            'proje sil',
            'projeye personel ata',
            'proje yönericisi ata',
            'personele görev ata',
            'şirket ekle'
        ];

        foreach ($permissions as $permission) {
        \Spatie\Permission\Models\Permission::firstOrCreate([
            'name' => $permission,
            'guard_name' => 'web'
        ]);
        }

        // 2. Rolleri Oluştur ve İzinleri Ata
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        // Şirket Yöneticisi: Her şeyi yapabilir
        $adminRole = Role::create(['name' => 'Sirket Yoneticisi']);
        $adminRole->givePermissionTo([
            'proje görüntüle',
            'proje oluştur',
            'proje düzenle',
            'proje sil',
            'projeye personel ata',
            'proje yönericisi ata',
            'personele görev ata'
        ]);

        // Personel: Sadece projeleri görebilir ve görev yönetebilir
        $staffRole = Role::create(['name' => 'Personel']);
        $staffRole->givePermissionTo([
            'proje görüntüle',
        ]);
        
        $managerRole = Role::create(['name' => 'Proje Yoneticisi']);
        $managerRole->givePermissionTo([
            'proje görüntüle',
            'proje oluştur',
            'proje düzenle',
            'projeye personel ata',
        ]);

        // Super Admin (Sizin için): Tüm sistemi yönetir
        $Superadmin = Role::create(['name' => 'Super Admin']);
        $Superadmin->givePermissionTo([
            'şirket ekle',
        ]);
    }
}
