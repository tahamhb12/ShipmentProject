<?php

namespace App\Filament\Resources\RejectedShipmentResource\Pages;

use App\Filament\Resources\RejectedShipmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRejectedShipment extends EditRecord
{
    protected static string $resource = RejectedShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
