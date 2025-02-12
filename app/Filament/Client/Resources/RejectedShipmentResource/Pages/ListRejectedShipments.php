<?php

namespace App\Filament\Client\Resources\RejectedShipmentResource\Pages;

use App\Filament\Client\Resources\RejectedShipmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRejectedShipments extends ListRecords
{
    protected static string $resource = RejectedShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
