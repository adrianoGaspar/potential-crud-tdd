<?php

namespace Database\Factories;

use App\Models\Developer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DeveloperFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Developer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'gender'    => 'male',
            'age' => $this->faker->numberBetween(15, 80),
            'hobby'     => $this->faker->sentence(),
            'birthday'  => $this->faker->dateTimeBetween('-60 years', '-15 years'),
        ];
    }
}
