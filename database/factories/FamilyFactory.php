<?php
namespace Database\Factories;

use App\Models\Family;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FamilyFactory extends Factory
{
    protected $model = Family::class;

    public function definition()
    {
        return [
            'name' => $this->faker->lastName() . ' Family',
            'description' => $this->faker->paragraph(),
            'user_id' => User::factory(),
        ];
    }
}
