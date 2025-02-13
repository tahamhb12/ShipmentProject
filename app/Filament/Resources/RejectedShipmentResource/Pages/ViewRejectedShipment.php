<?php

namespace App\Filament\Resources\RejectedShipmentResource\Pages;

use App\Filament\Resources\RejectedShipmentResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewRejectedShipment extends ViewRecord
{
    protected static string $resource = RejectedShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Action::make('downloadFiles')
            ->label('Download Files')
            ->icon('heroicon-o-arrow-down-tray')
            ->url(fn ($record) => route('shipments.download', $record))
        ];
    }
}
