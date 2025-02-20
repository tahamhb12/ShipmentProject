<?php

namespace Database\Factories;

use App\Models\Carrier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarrierFactory extends Factory
{

    protected $model = Carrier::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'logo' => $this->faker->imageUrl(100, 100, 'business'),
            'contact_email' => $this->faker->companyEmail,
        ];
    }
}
