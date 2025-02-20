<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'client_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 500),
            'method' => $this->faker->randomElement(['Cheque', 'Virment', 'Cash']),
            'date' => $this->faker->dateTimeThisYear(),
            'attachment' => null,
            'details' => $this->faker->sentence,
        ];
    }
}
