<?php

declare(strict_types=1);

namespace Database\Seeders\Catat;

use App\Models\Catat\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

class CtClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultUserId = User::query()->value('id');

        if ($defaultUserId === null) {
            return;
        }

        $clients = [
            [
                'name' => 'Budi Santoso',
                'company' => 'PT Nusantara Teknologi',
                'email' => 'budi@nusatek.co.id',
                'phone' => '+62 812 1111 0001',
                'status' => 'active',
                'notes' => 'Client enterprise untuk project ERP internal.',
                'created_by' => $defaultUserId,
            ],
            [
                'name' => 'Sari Wulandari',
                'company' => 'CV Kreatif Media',
                'email' => 'sari@kreatifmedia.id',
                'phone' => '+62 812 1111 0002',
                'status' => 'active',
                'notes' => 'Fokus maintenance website company profile.',
                'created_by' => $defaultUserId,
            ],
            [
                'name' => 'Andi Pratama',
                'company' => 'Freelance Owner',
                'email' => 'andi@freelance.id',
                'phone' => '+62 812 1111 0003',
                'status' => 'inactive',
                'notes' => 'Project lama, status ditahan sementara.',
                'created_by' => $defaultUserId,
            ],
        ];

        foreach ($clients as $client) {
            Client::updateOrCreate(
                ['email' => $client['email']],
                $client
            );
        }
    }
}
