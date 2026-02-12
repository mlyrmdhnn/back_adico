<?php

namespace Database\Factories;

use App\Models\AttendanceType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'salesman_id' => User::inRandomOrder()->value('id'),
            'date' => fake()->dateTimeBetween('2025-01-01', '2026-2-02'),
            'check_in_at' => fake()->dateTime(),
            'attendance_type_id' => AttendanceType::inRandomOrder()->value('id'),
        ];
    }
}
