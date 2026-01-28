<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Şirket
        Company::firstOrCreate(
            ['tax_number' => '1111111111'], // Eğer bu vergi numarası varsa tekrar ekleme
            ['name' => 'Yazılım ve Teknoloji']
        );

        // 2. Şirket
        Company::firstOrCreate(
            ['tax_number' => '2222222222'],
            ['name' => 'Global İnşaat A.Ş.']
        );

        // 3. Şirket (Örnek)
        Company::firstOrCreate(
            ['tax_number' => '3333333333'],
            ['name' => 'E-Ticaret Çözümleri Ltd.']
        );
    }
}
