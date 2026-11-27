<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::inRandomOrder()->limit(120)->get();

        foreach ($students as $student) {
            for ($i = 0; $i < 6; $i++) {
                $date = now()->subMonths($i)->startOfMonth()->addDays(rand(0, 25));

                Payment::create([
                    'student_id' => $student->id,
                    'amount'     => fake()->randomElement([30, 40, 50, 60]),
                    'paid_at'    => $date,
                    'month'      => $date->format('Y-m'),
                    'method'     => fake()->randomElement(['Cash', 'Bank', 'ABA', 'Wing']),
                    'status'     => fake()->randomElement(['Paid', 'Paid', 'Paid', 'Pending', 'Overdue']),
                ]);
            }
        }
    }
}