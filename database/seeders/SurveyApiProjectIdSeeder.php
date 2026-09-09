<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SurveyApiProjectIdSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('surveys')
            ->whereNull('api_project_id')
            ->update([
                'api_project_id' => 1,
            ]);

        DB::table('questions')
            ->whereNull('project_id')
            ->update([
                'project_id' => 1,
            ]);
        $project = DB::table('projects')
            ->where('code', 'MX')
            ->first();

        if (!$project) {
            DB::table('projects')->updateOrInsert(
                ['id' => 1],
                [
                    'code' => 'MX',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
