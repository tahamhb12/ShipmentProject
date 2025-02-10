<?php

namespace App\Filament\Client\Resources\ShipmentResource\Pages;

use App\Filament\Client\Resources\ShipmentResource;
use App\Models\Address;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateShipment extends CreateRecord
{
    protected static string $resource = ShipmentResource::class;

    protected function afterCreate(): void
    {
        $receiver = $this->form->getState()['receiver'];
        $street_address = $this->form->getState()['street_address'];
        $state = $this->form->getState()['state'];
        $city = $this->form->getState()['city'];
        $country = $this->form->getState()['country'];
        $postal_code = $this->form->getState()['postal_code'];
        $user = User::firstOrCreate(
            ['email' => Str::slug($receiver).'@gmail.com'],
            [
                'name' => $receiver,
                'password' => Hash::make(Str::slug($receiver))
            ]
        );
        $this->record->receiver_id = $user->id;
        $address = Address::create([
            'user_id'=>auth()->user()->id,
            'street_address'=>$street_address,
            'state'=>$state,
            'city'=>$city,
            'country'=>$country,
            'postal_code'=>$postal_code,
        ]);
        $this->record->save();
    }
}
