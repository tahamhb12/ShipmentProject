<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;


    protected static ?string $navigationIcon = 'heroicon-s-credit-card';
    protected static ?string $navigationGroup = 'Shipments';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('client_id')->options(User::pluck('name','id'))->required()->searchable()->label('Client'),
                TextInput::make('amount')->required()->numeric(),
                Select::make('method')->options(['Cheque','Virment','Cash'])->required(),
                DatePicker::make('date')->required(),
                FileUpload::make('attachment')->multiple(),
                Textarea::make('details')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client.name')->searchable(),
                TextColumn::make('amount')->formatStateUsing(fn($state)=>$state.' DH'),
                TextColumn::make('method'),
                TextColumn::make('date')->date(),
                TextColumn::make('details')->limit(30),
                ImageColumn::make('attachment'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('downloadFiles')
                ->label('Download Files')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn ($record) => route('payments.download', $record))
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'view' => Pages\ViewPayment::route('/{record}'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
