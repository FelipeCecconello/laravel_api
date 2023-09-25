<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Registro;

class RegistroFactory extends Factory
{
    protected $model = Registro::class;

    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(['denuncia', 'sugestao', 'duvida']),
            'message' => $this->faker->text(200),
            'is_identified' => $this->faker->boolean,
            'whistleblower_name' => $this->faker->name,
            'whistleblower_birth' => $this->faker->date,
            'created_at' => $this->faker->dateTimeThisMonth,
            'deleted' => $this->faker->boolean,
        ];
    }
}
