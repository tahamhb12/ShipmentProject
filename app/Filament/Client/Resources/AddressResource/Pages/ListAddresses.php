<?php

namespace App\Filament\Client\Resources\AddressResource\Pages;

use App\Filament\Client\Resources\AddressResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAddresses extends ListRecords
{
    protected static string $resource = AddressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
