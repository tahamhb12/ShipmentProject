<?php

namespace App\Filament\Manager\Resources\AddressResource\Pages;

use App\Filament\Manager\Resources\AddressResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAddress extends ViewRecord
{
    protected static string $resource = AddressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
