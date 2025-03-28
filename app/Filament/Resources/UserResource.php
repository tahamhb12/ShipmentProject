<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Payment;
use App\Models\Shipment;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Users';

    protected static ?string $navigationIcon = 'heroicon-s-users';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('email')->unique(ignoreRecord:true)->required(),
                TextInput::make('password')->password()->required()->visibleOn('create'),
                TextInput::make('password_confirmation')
                ->password()
                ->same('password')->required()->visibleOn('create'),
                Select::make('role')->options(['Admin'=>'Admin','Manager'=>'Manager','Client'=>'Client','Accountant'=>'Accountant'])->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name")->searchable(),
                TextColumn::make("email")->searchable(),
                TextColumn::make("role"),
                TextColumn::make("updated_at")
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
                SelectFilter::make('role')
                ->options([
                    'Admin'=>'Admin',
                    'Manager'=>'Manager',
                    'Accountant'=>'Accountant',
                    'Client'=>'Client',
                ])->multiple()
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
