<?php

namespace App\Filament\Client\Resources\ApprovedShipmentResource\Pages;

use App\Filament\Client\Resources\ApprovedShipmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApprovedShipment extends EditRecord
{
    protected static string $resource = ApprovedShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
