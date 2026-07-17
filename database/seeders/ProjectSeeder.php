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
            '3AS_API' => 'Alok Project',
        ];

        foreach ($projects as $name => $description) {
            Project::updateOrCreate(
                ['name' => $name],
                [
                    'description' => $description,
                    'status' => 1,
                ]
            );
        }

    }
}
