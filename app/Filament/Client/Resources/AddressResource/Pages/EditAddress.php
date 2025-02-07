<?php

namespace App\Filament\Client\Resources\AddressResource\Pages;

use App\Filament\Client\Resources\AddressResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAddress extends EditRecord
{
    protected static string $resource = AddressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
