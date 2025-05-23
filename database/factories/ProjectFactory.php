<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->numberBetween(100, 1000),
            'customer_id' => $this->faker->numberBetween(1,15),
            'type' => $this->faker->randomElement(['Activo', 'Soporte', 'Cerrado', 'Entregado', 'No activo']),
            'name' => $this->faker->name(),
            'priority' => $this->faker->numberBetween(1,10),
            'questions_priority' => json_encode([
                'severity' => $this->faker->randomElement([0, 6, 18, 24]),
                'impact' => $this->faker->randomElement([0, 10, 15, 20]),
                'satisfaction' => $this->faker->randomElement([0, 6, 12, 18, 24]),
                'temporality' => $this->faker->randomElement([0, 4, 12, 16]),
                'magnitude' => $this->faker->randomElement([0, 2, 6, 8]),
                'strategy' => $this->faker->randomElement([0, 6, 9, 12]),
                'stage' => $this->faker->randomElement([0, 1, 2, 3, 4]),
            ]),
        ];
    }
}
