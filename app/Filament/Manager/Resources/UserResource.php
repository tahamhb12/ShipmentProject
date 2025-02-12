<?php

namespace App\Filament\Manager\Resources;

use App\Filament\Manager\Resources\UserResource\Pages;
use App\Filament\Manager\Resources\UserResource\RelationManagers;
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

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $label = 'Clients';

    protected static ?string $navigationGroup = 'Clients';



    protected static ?string $navigationIcon = 'heroicon-s-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('email')->unique()->required(),
                TextInput::make('password')->password()->required()->visibleOn('create'),
                TextInput::make('password_confirmation')
                ->password()
                ->same('password')->required()->visibleOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(User::query()->whereNot('role','Admin'))
            ->columns([
                TextColumn::make("name"),
                TextColumn::make("email"),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
