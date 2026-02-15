<?php

namespace Database\Seeders;

use App\Models\System;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
        'أنظمة مركزية',
        'أنظمة الأمن والرقابة',
        'أنظمة تشغيلية وخدمية',
        'أنظمة إدارية ومالية',
        'أنظمة الدفع والتحصيل',
        'أنظمة التوثيق والأرشفة',
        'المواقع والبوابات',
        'التطبيقات الذكية',
    ];

    foreach ($types as $type) {
        System::create(['name' => $type]);
    }
    }
}
