<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // សិស្ស 300 នាក់
        $students = Student::factory()->count(300)->create();

        // កំណត់ត្រាវត្តមាន ៥ ថ្ងៃចុងក្រោយ
        foreach ($students as $student) {
            for ($i = 0; $i < 5; $i++) {
                Attendance::create([
                    'student_id' => $student->id,
                    'date'       => now()->subDays($i)->toDateString(),
                    'status'     => fake()->randomElement([
                        'present', 'present', 'present', 'present', 'absent', 'leave',
                    ]),
                ]);
            }
        }
    }
}