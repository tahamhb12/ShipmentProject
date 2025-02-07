<?php

namespace App\Filament\Client\Resources;

use App\Filament\Client\Resources\AddressResource\Pages;
use App\Filament\Client\Resources\AddressResource\RelationManagers;
use App\Models\Address;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressResource extends Resource
{
    protected static ?string $model = Address::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('street_address')->required(),
                TextInput::make('city')->required(),
                TextInput::make('state')->required(),
                TextInput::make('postal_code')->required(),
                TextInput::make('country')->required(),
            ]);
   }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('street_address'),
                TextColumn::make('city'),
                TextColumn::make('state'),
                TextColumn::make('postal_code'),
                TextColumn::make('country'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAddresses::route('/'),
            'create' => Pages\CreateAddress::route('/create'),
            'view' => Pages\ViewAddress::route('/{record}'),
            'edit' => Pages\EditAddress::route('/{record}/edit'),
        ];
    }
}
