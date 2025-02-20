<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Shipment;
use App\Models\Payment;
use App\Models\Carrier;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $client = User::create([
            'name' => 'Client User',
            'email' => 'client@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        $accountant = User::create([
            'name' => 'Accountant User',
            'email' => 'accountant@example.com',
            'password' => Hash::make('password'),
            'role' => 'accountant',
        ]);

        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        $carrier = Carrier::factory()->create();

        Shipment::factory(5)->create([
            'user_id' => $admin->id,
            'receiver_id' => $client->id,
            'carrier_id' => $carrier->id,
        ]);

        Payment::factory(5)->create(['client_id' => $client->id]);

        Address::factory(5)->create(['user_id' => $client->id]);
    }
}
