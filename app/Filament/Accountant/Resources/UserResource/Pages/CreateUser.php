<?php

namespace App\Filament\Accountant\Resources\UserResource\Pages;

use App\Filament\Accountant\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
