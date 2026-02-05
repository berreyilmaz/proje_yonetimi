<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();

        // --- 1. ŞİRKET KULLANICILARI (Yazılım) ---
        if (isset($companies[0])) {
            $companyOwner0 = User::create([
                'name' => 'Ahmet Yılmaz',
                'email' => 'ahmet@yazilim.com',
                'password' => Hash::make('123'), // Özel Şifre
                'company_id' => $companies[0]->id
            ]);
            $companyOwner0->assignRole('Sirket Yoneticisi');
            
            $operationManager0 = User::create([
                'name' => 'Selin Arslan',
                'email' => 'selin@yazilim.com',
                'password' => Hash::make('123'), // Özel Şifre
                'company_id' => $companies[0]->id
            ]);
            $operationManager0->assignRole('Operasyon Yoneticisi');

            $projectManager0 = User::create([
                'name' => 'Ömer Yılmaz',
                'email' => 'omer@yazilim.com',
                'password' => Hash::make('123'), // Özel Şifre
                'company_id' => $companies[0]->id
            ]);
            $projectManager0->assignRole('Proje Yoneticisi');

            $teamLead0 = User::create([
                'name' => 'Buse Çelik',
                'email' => 'buse@yazilim.com',
                'password' => Hash::make('123'), // Özel Şifre
                'company_id' => $companies[0]->id
            ]);
            $teamLead0->assignRole('Takim Lideri');

            $employee0 = User::create([
                'name' => 'Mehmet Demir',
                'email' => 'mehmet@yazilim.com',
                'password' => Hash::make('123'), // Özel Şifre
                'company_id' => $companies[0]->id
            ]);
            $employee0->assignRole('Personel');

            $financeOfficer0 = User::create([
                'name' => 'Sude Korkmaz',
                'email' => 'sude@yazilim.com',
                'password' => Hash::make('123'), // Özel Şifre
                'company_id' => $companies[0]->id
            ]);
            $financeOfficer0->assignRole('Finans Görevlisi');

            $client0 = User::create([
                'name' => 'Ece Arı',
                'email' => 'ece@yazilim.com',
                'password' => Hash::make('123'), // Özel Şifre
                'company_id' => $companies[0]->id
            ]);
            $client0->assignRole('Musteri');

        }

        // --- 2. ŞİRKET KULLANICILARI (İnşaat) ---
        if (isset($companies[1])) {
            $u3 = User::create([
                'name' => 'Ayşe Kaya',
                'email' => 'ayse@insaat.com',
                'password' => Hash::make('ayse789'), // Özel Şifre
                'company_id' => $companies[1]->id
            ]);
            $u3->assignRole('Sirket Yoneticisi');

            $u4 = User::create([
                'name' => 'Fatma Şahin',
                'email' => 'fatma@insaat.com',
                'password' => Hash::make('fatma321'), // Özel Şifre
                'company_id' => $companies[1]->id
            ]);
            $u4->assignRole('Personel');
        }

        // --- 3. ŞİRKET KULLANICILARI (Lojistik) ---
        if (isset($companies[2])) {
            $u5 = User::create([
                'name' => 'Caner Özkan',
                'email' => 'caner@lojistik.com',
                'password' => Hash::make('caner654'), // Özel Şifre
                'company_id' => $companies[2]->id
            ]);
            $u5->assignRole('Sirket Yoneticisi');
        }


        // --- GLOBAL ADMIN ---
        $admin = User::create([
            'name' => 'Yazılım Sistem',
            'email' => 'admin@sistem.com',
            'password' => Hash::make('123'), // Özel Şifre
            'company_id' => $companies[0]->id
        ]);
        $admin->assignRole('Super Admin');

    }
}
