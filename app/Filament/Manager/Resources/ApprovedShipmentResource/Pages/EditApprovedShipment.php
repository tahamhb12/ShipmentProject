<?php

namespace App\Filament\Manager\Resources\ApprovedShipmentResource\Pages;

use App\Filament\Manager\Resources\ApprovedShipmentResource;
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
