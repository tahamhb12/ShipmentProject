<?php

namespace App\Filament\Client\Resources\PendingShipmentResource\Pages;

use App\Filament\Client\Resources\PendingShipmentResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;

class ViewPendingShipment extends ViewRecord
{
    protected static string $resource = PendingShipmentResource::class;

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
