<?php

namespace App\Filament\Accountant\Resources\PaymentResource\Pages;

use App\Filament\Accountant\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;
}
