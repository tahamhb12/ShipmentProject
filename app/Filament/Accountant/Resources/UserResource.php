<?php

namespace App\Filament\Accountant\Resources;

use App\Filament\Accountant\Resources\UserResource\Pages;
use App\Filament\Accountant\Resources\UserResource\RelationManagers;
use App\Models\Payment;
use App\Models\Shipment;
use App\Models\User;
use Filament\Forms;
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

    protected static ?string $navigationIcon = 'heroicon-s-users';
    protected static ?string $label = 'Clients';

    protected static ?string $navigationGroup = 'Clients';


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
        ->query(User::query()->where('role','Client'))
            ->columns([
                TextColumn::make("name")->searchable(),
                TextColumn::make("email")->searchable(),
                TextColumn::make("role")
                ->label('Total Paid')
                ->formatStateUsing(function($record){
                    $total_paid = Payment::where('client_id',$record->id)->sum('amount');
                    return number_format($total_paid,0) . ' DH';
                })
                ->color('success')
                ->badge(),
                TextColumn::make("created_at")
                ->label('Total Unpaid')
                ->formatStateUsing(function($record){
                    $total_paid = Payment::where('client_id',$record->id)->sum('amount');
                    $total_shipment_cost = Shipment::where('status','approved')
                    ->where('user_id',$record->id)
                    ->sum('shipment_price');
                    return number_format($total_shipment_cost-$total_paid,0) . ' DH';
                })
                ->color('danger')
                ->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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

    public static function canCreate(): bool
    {
        return false;
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
