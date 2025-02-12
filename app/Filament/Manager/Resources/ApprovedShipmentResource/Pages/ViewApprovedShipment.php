<?php

namespace App\Filament\Manager\Resources\ApprovedShipmentResource\Pages;

use App\Filament\Manager\Resources\ApprovedShipmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewApprovedShipment extends ViewRecord
{
    protected static string $resource = ApprovedShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
