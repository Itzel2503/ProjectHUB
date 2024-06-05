<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->firstName . ' ' . $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('Arten.123!'), // Es mejor no utilizar contraseñas predeterminadas para datos aleatorios
            'type_user' => $this->faker->randomElement([1, 2, 3]), // Suponiendo que tienes varios tipos de usuarios
            'date_birthday' => $this->faker->date(),
            'area_id' => $this->faker->numberBetween(1,5), // Asumiendo que tienes áreas del 1 al 10
            'entry_date' => $this->faker->date(),
            'effort_points' => $this->faker->numberBetween(10, 100),

            // 'name' => $this->faker->name(),
            // 'email' => $this->faker->unique()->safeEmail(),
            // 'email_verified_at' => now(),
            // 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            // 'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
