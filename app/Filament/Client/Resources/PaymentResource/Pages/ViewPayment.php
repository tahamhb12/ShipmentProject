<?php

namespace App\Filament\Client\Resources\PaymentResource\Pages;

use App\Filament\Client\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPayment extends ViewRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
