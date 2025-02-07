<?php

namespace App\Filament\Client\Resources\ShipmentResource\Pages;

use App\Filament\Client\Resources\ShipmentResource;
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
        $user = User::firstOrCreate(
            ['email' => Str::slug($receiver).'@gmail.com'], // ✅ Correct format for lookup
            [
                'name' => $receiver,
                'password' => Hash::make(Str::slug($receiver))
            ]
        );
        $this->record->receiver_id = $user->id;
        $this->record->save();
    }
}
