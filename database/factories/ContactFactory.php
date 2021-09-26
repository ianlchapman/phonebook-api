<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'phone_home' => $this->faker->phoneNumber(),
            'phone_mobile' => $this->faker->phoneNumber()
        ];
    }

    /**
     * Indicate the contact should be deleted
     *
     * @return ContactFactory
     */
    public function deleted() : ContactFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'deleted_at' => now()
            ];
        });
    }
}
