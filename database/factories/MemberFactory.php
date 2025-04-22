<?php
namespace Database\Factories;

use App\Models\Family;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition()
    {
        $gender = $this->faker->randomElement(['male', 'female', 'other']);
        $birthDate = $this->faker->dateTimeBetween('-100 years', '-20 years');
        $isDead = $this->faker->boolean(30);

        return [
            'name' => $this->faker->name($gender == 'male' ? 'male' : ($gender == 'female' ? 'female' : null)),
            'photo' => null,
            'birth_date' => $birthDate,
            'birth_place' => $this->faker->city(),
            'death_date' => $isDead ? $this->faker->dateTimeBetween($birthDate, 'now') : null,
            'death_place' => $isDead ? $this->faker->city() : null,
            'occupation' => $this->faker->jobTitle(),
            'bio' => $this->faker->paragraphs(3, true),
            'gender' => $gender,
            'family_id' => Family::factory(),
            'parent_id' => null,
        ];
    }
}
