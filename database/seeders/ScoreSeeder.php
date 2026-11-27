<?php

namespace Database\Seeders;

use App\Models\Score;
use App\Models\Student;
use Illuminate\Database\Seeder;

class ScoreSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = ['Mathematics', 'English', 'Physics', 'Chemistry'];
        $studentIds = Student::inRandomOrder()->limit(150)->pluck('id');
        $rows = [];

        foreach ($studentIds as $id) {
            $base = rand(45, 85);

            foreach (range(5, 0) as $i) {
                $month = now()->subMonths($i)->format('Y-m');

                foreach ($subjects as $subject) {
                    // ពិន្ទុមានទំនោរឡើងបន្តិចៗតាមខែ
                    $score = $base + (5 - $i) * rand(0, 3) + rand(-8, 8);

                    $rows[] = [
                        'student_id' => $id,
                        'subject'    => $subject,
                        'month'      => $month,
                        'score'      => max(0, min(100, $score)),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            Score::insert($chunk);
        }
    }
}