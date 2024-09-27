<?php

namespace Database\Factories;

use App\Models\Vote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class VoteFactory.
 */
class VoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'application_id' => 1,
            'message' => $this->faker->realText(),
            'status' => $this->faker->randomElement([Vote::APPROVED, Vote::DISAPPROVED]),
        ];
    }
}
