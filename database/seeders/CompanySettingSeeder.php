<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use Illuminate\Database\Seeder;

class CompanySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanySetting::firstOrCreate(
            ['company_name' => 'AWALAN'],
            [
                'address' => 'Jl. Kaki Pegel Sekali No. 1, Bekasi Kabupaten, Jawa Barat, Indonesia',
                'phone' => '+62 21 1234 5678',
                'email' => 'info@mail.id',
                'website' => 'https://perusahaanmu.id',
                'description' => 'Starter aplikasi AWALAN.',
                'logo' => null,
                'favicon' => null,
                'login_background' => null,
                'primary_color' => '#3B82F6',
                'secondary_color' => '#10B981',
                'is_active' => true,
            ]
        );
    }
}
