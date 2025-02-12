<?php

namespace App\Filament\Client\Resources\ApprovedShipmentResource\Pages;

use App\Filament\Client\Resources\ApprovedShipmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApprovedShipments extends ListRecords
{
    protected static string $resource = ApprovedShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
