<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BarangaySeeder::class,
            ElectoralDataSeeder::class,
            AssistanceSeeder::class,
            EventSeeder::class,
            DirectorySeeder::class,
            IssueSeeder::class,
            SurveySeeder::class,
        ]);
    }
}
