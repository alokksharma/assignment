<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $projects = [
            'AS_API' => 'Alok Project',
        ];

        foreach ($projects as $name => $description) {
            Project::firstOrCreate(
                ['name' => $name],
                [
                    'description' => $description,
                    'status' => 1,
                    'created_at' => now(),
                ]
            );
        }

    }
}
