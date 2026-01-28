<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Önce tabloyu tamamen boşaltalım (temiz başlangıç)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Project::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. İlk şirketi bulalım, yoksa hemen bir tane oluşturalım
        $company = Company::first();
        if (!$company) {
            $company = Company::create(['name' => 'Varsayılan Şirket']);
        }

        // 3. Verileri ekle
        Project::create([
            'company_id' => $company->id,
            'title' => 'E-Ticaret Sitesi Geliştirme',
            'description' => 'Müşteri paneli ve ödeme entegrasyonu.',
            'status' => 'devam_ediyor',
            'start_date' => '2026-01-01',
        ]);

        Project::create([
            'company_id' => $company->id,
            'title' => 'Mobil Uygulama Tasarımı',
            'description' => 'iOS ve Android için yeni arayüz.',
            'status' => 'tamamlandi',
            'start_date' => '2025-06-01',
            'end_date' => '2025-12-20',
        ]);

        echo "\n --- PROJELER BASARIYLA EKLENDI! ---\n";
    }
}