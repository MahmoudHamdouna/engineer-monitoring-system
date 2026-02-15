<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Team;
use App\Models\System;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        // جلب كل الفرق والأنظمة
        $teams = Team::all();
        $systems = System::all();

        $projects = [
            ['name' => 'Website Redesign', 'description' => 'Redesign the company website for better UX.'],
            ['name' => 'Mobile App Launch', 'description' => 'Release the new mobile app on iOS and Android.'],
            ['name' => 'Payment Gateway Integration', 'description' => 'Integrate secure payment systems.'],
            ['name' => 'AI Chatbot', 'description' => 'Develop AI-powered customer support bot.'],
            ['name' => 'Database Optimization', 'description' => 'Optimize DB queries and indexing.'],
            ['name' => 'Internal Dashboard', 'description' => 'Build an internal dashboard for project monitoring.'],
            ['name' => 'Cloud Migration', 'description' => 'Move services to cloud infrastructure.'],
            ['name' => 'Marketing Website', 'description' => 'Develop landing pages and marketing assets.'],
            ['name' => 'Security Audit', 'description' => 'Conduct full system security audit.'],
            ['name' => 'IoT Smart App', 'description' => 'Develop smart home IoT application.'],
        ];

        foreach ($projects as $index => $proj) {
            Project::create([
                'name' => $proj['name'],
                'description' => $proj['description'],
                'team_id' => $teams->random()->id,        // ربط المشروع بفريق عشوائي
                'system_id' => $systems->isNotEmpty() ? $systems->random()->id : null, // ربط النظام إذا موجود
                'start_date' => now()->subDays(rand(0, 30)),  // تاريخ بداية عشوائي خلال آخر 30 يوم
                'end_date' => now()->addDays(rand(15, 90)),   // تاريخ نهاية عشوائي خلال 15-90 يوم
                'status' => ['planning', 'active', 'completed', 'on_hold'][rand(0, 3)],
            ]);
        }
    }
}
