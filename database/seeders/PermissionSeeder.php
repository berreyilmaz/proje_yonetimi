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
        $permissionGroups = [
            'şirket' => [
                'şirket.goruntule',
                'şirket.duzenle',
                'şirket.sil',
            ],
            'kullanıcı' => [
                'kullanici.liste',
                'kullanici.ekle',
                'kullanici.duzenle',
                'kullanici.sil',
            ],
            'proje' => [
                'proje.goruntule',
                'proje.ekle',
                'proje.duzenle',
                'proje.sil',
                'projeye.personel.ata',
            ],
            'operasyon' => [
                'operasyon.surec-yonetimi',
                'operasyon.yonetim',
                'gorev.goruntule',
                'gorev.ekle',
                'gorev.duzenle',
                'gorev.sil',



            ],
            'finans' => [
                'finans.goruntule',
                'finans.muhasebe',
                'finans.fatura-yonetimi',
                'fatura.goruntule',
                'fatura.olustur',
                'fatura.duzenle',
                'fatura.sil',
            ],
            'raporlama' => [
                'rapor.proje-bazli',
                'rapor.goruntule',
                'rapor.olustur',
            ],
        ];

        foreach ($permissionGroups as $group => $permissions) {
            foreach ($permissions as $permissionName) {
                Permission::findOrCreate($permissionName);
            }
        }

        // 2. Rolleri Oluştur ve İzinleri Ata
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $superAdmin = Role::findOrCreate('Super Admin');
        $superAdmin->givePermissionTo(Permission::all());


        // Şirket Yöneticisi: Her şeyi yapabilir
        $companyOwner = Role::firstOrCreate(['name' => 'Sirket Yoneticisi']);
        $companyOwner->givePermissionTo([
            'gorev.goruntule',
            'proje.goruntule',
            'finans.goruntule',
            'kullanici.liste',
            'kullanici.ekle',
            'kullanici.duzenle',
            'kullanici.sil',

        ]);

        $operationsManager = Role::firstOrCreate(['name' => 'Operasyon Yoneticisi']);
        $operationsManager->givePermissionTo([
            'operasyon.surec-yonetimi',
            'operasyon.yonetim',
            'gorev.goruntule',
            'gorev.ekle',
            'gorev.duzenle',
            'gorev.sil',
            'proje.goruntule',
        ]);
        
        $projectManager = Role::firstOrCreate(['name' => 'Proje Yoneticisi']);
        $projectManager->givePermissionTo([
            'proje.goruntule',
            'proje.duzenle',
            ]);
            
        $teamLead = Role::firstOrCreate(['name' => 'Takim Lideri']);
        $teamLead->givePermissionTo([
            'proje.goruntule',
            'proje.duzenle',
            'proje.ekle',
            'projeye.personel.ata',
            ]);
                
        // Personel: Sadece projeleri görebilir ve görev yönetebilir
        $employee = Role::firstOrCreate(['name' => 'Personel']);
        $employee->givePermissionTo([
            'proje.goruntule',
            'gorev.goruntule',
        ]);
        $financeOfficer = Role::firstOrCreate(['name' => 'Finans Gorevlisi']);;
        $financeOfficer->givePermissionTo([
            'proje.goruntule',
            'finans.goruntule',
            'finans.muhasebe',
            'finans.fatura-yonetimi',
        ]); 

        $client = Role::firstOrCreate(['name' => 'Musteri']);
        $client->givePermissionTo([
            'proje.goruntule',
            'fatura.goruntule',
        ]);
    
    }
}
