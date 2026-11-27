<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class MasterInfoSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = collect([
            ['name' => 'Sok Dara',    'subject' => 'Mathematics', 'phone' => '012 345 678'],
            ['name' => 'Chan Sreyka', 'subject' => 'English',     'phone' => '098 765 432'],
            ['name' => 'Vann Rithy',  'subject' => 'Physics',     'phone' => '077 111 222'],
            ['name' => 'Kim Sophea',  'subject' => 'Chemistry',   'phone' => '015 333 444'],
        ])->map(fn ($t) => Teacher::create($t + ['status' => 'Active']));

        foreach (['Group A', 'Group B', 'Group C', 'Group D'] as $i => $name) {
            Group::create([
                'name'       => $name,
                'teacher_id' => $teachers[$i]->id,
            ]);
        }
    }
}