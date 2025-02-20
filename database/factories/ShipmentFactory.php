<?php

namespace Database\Factories;

use App\Models\Shipment;
use App\Models\User;
use App\Models\Carrier;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipmentFactory extends Factory
{
    protected $model = Shipment::class;

    public function definition()
    {
        return [
            'receiver_id' => User::factory(),
            'user_id' => User::factory(),
            'weight' => $this->faker->randomFloat(2, 1, 20),
            'isFlex' => $this->faker->boolean,
            'value' => $this->faker->randomFloat(2, 50, 1000),
            'tracking_number' => strtoupper($this->faker->bothify('TRACK###??')),
            'carrier_id' => Carrier::factory(),
            'attachment' => null,
            'shipment_price' => $this->faker->randomFloat(2, 5, 50),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'reason' => 'bad one',
            'street_address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'postal_code' => $this->faker->postcode,
            'country' => $this->faker->country,
            'description' => $this->faker->sentence,
        ];
    }
}
