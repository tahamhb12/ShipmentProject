<?php

namespace App\Filament\Manager\Resources\PendingShipmentResource\Pages;

use App\Filament\Manager\Resources\PendingShipmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPendingShipment extends EditRecord
{
    protected static string $resource = PendingShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
