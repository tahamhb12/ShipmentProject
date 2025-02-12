<?php

namespace App\Filament\Manager\Resources\RejectedShipmentResource\Pages;

use App\Filament\Manager\Resources\RejectedShipmentResource;
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
