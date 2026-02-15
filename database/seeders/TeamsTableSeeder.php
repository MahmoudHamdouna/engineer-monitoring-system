<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\User;

class TeamsTableSeeder extends Seeder
{
    public function run()
    {

        $leaders = User::where('role', 'leader')->get();

        $teams = [
            ['name' => 'Frontend Team', 'specialization' => 'Web Development', 'description' => 'مسؤول عن تطوير واجهات المستخدم.'],
            ['name' => 'Backend Team', 'specialization' => 'Server & APIs', 'description' => 'مسؤول عن البنية الخلفية وقواعد البيانات.'],
            ['name' => 'Mobile Apps Team', 'specialization' => 'iOS & Android', 'description' => 'تطوير تطبيقات الهواتف.'],
            ['name' => 'AI & ML Team', 'specialization' => 'Artificial Intelligence', 'description' => 'مشاريع الذكاء الاصطناعي.'],
            ['name' => 'Database Team', 'specialization' => 'DB Management', 'description' => 'إدارة قواعد البيانات.'],
            ['name' => 'Design Team', 'specialization' => 'UI/UX & Graphics', 'description' => 'تصميم واجهات المستخدم والمواد التسويقية.'],
        ];

        foreach ($teams as $index => $team) {
            Team::create([
                'name' => $team['name'],
                'specialization' => $team['specialization'],
                'description' => $team['description'],
                'leader_id' => $leaders[$index % $leaders->count()]->id ?? null,
            ]);
        }
    }
}
