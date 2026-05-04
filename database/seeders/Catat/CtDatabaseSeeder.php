<?php

declare(strict_types=1);

namespace Database\Seeders\Catat;

use Database\Seeders\Catat\CtClientSeeder;
use Database\Seeders\Catat\CtMenuSeeder;
use Database\Seeders\Catat\CtPermissionSeeder;
use Database\Seeders\Catat\CtWorkspaceSeeder;
use Illuminate\Database\Seeder;

class CtDatabaseSeeder extends Seeder
{
    /**
     * Seed the Catat module data.
     */
    public function run(): void
    {
        $this->call([
            CtMenuSeeder::class,
            CtClientSeeder::class,
            CtWorkspaceSeeder::class,
            CtPermissionSeeder::class,
        ]);
    }
}
