<?php

namespace App\Filament\Client\Resources;

use App\Filament\Client\Resources\RejectedShipmentResource\Pages;
use App\Models\Carrier;
use App\Models\RejectedShipment;
use App\Models\Shipment;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;
use Parfaitementweb\FilamentCountryField\Infolists\Components\CountryEntry;

class RejectedShipmentResource extends Resource
{
    protected static ?string $model = Shipment::class;
    protected static ?string $label = 'Rejected';
    protected static ?string $pluralLabel = 'Rejected';

    protected static ?string $navigationParentItem = 'Shipments';
    protected static ?string $navigationGroup = 'Shipments';
    protected static ?int $navigationSort = -2;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form;
        //
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(Shipment::query()->where('status','rejected'))
        ->columns([
            TextColumn::make('receiver.name')->searchable(),
            TextColumn::make('carrier.name')->searchable(),
            TextColumn::make('weight')->formatStateUsing(fn($state)=>$state.' Kg'),
            TextColumn::make('value')->money('mad'),
            IconColumn::make('isFlex')->label('Flex')->boolean(),
            TextColumn::make("status")
            ->formatStateUsing(function($state){
                if($state == "approved") return 'Approved';
                if($state == "rejected") return 'Rejected';
                if($state == "pending") return 'Pending';
                })
                ->badge()
                ->icon('heroicon-s-x-circle')
                ->color(fn ($state) => match ($state) {
                    'pending' => 'warning',
                    "approved" => 'success',
                    "rejected" => 'danger',
                }),
            TextColumn::make("reason")
            ])
            ->filters([
                SelectFilter::make('carrier')
                ->relationship('carrier','name'),
                SelectFilter::make('isFlex')
                ->options([true=>'Yes',false=>'No'])->label('Flex?'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }


    public static function infolist(Infolist $infolist): Infolist{
        return $infolist
        ->schema([
            Group::make()->schema([
                ComponentsSection::make('Recipient Details')->schema([
                    TextEntry::make('receiver.name')->label('Name')
                ])->collapsible(),
                ComponentsSection::make('Address')->schema([
                    TextEntry::make('street_address')
                    ->label('Street Address')
                    ->columnSpanFull(),
                    TextEntry::make('city')
                        ->label('City'),
                    TextEntry::make('state')
                        ->label('State'),
                    TextEntry::make('postal_code')
                        ->label('Postal Code'),
                    CountryEntry::make('country')
                        ->label('Country'),
                ])->collapsible(),
            ]),

            Group::make()->schema([
                ComponentsSection::make('Details')->schema([
                    TextEntry::make("status")
                    ->formatStateUsing(function($state){
                        if($state == "approved") return 'Approved';
                        if($state == "rejected") return 'Rejected';
                        if($state == "pending") return 'Pending';
                    })
                        ->badge()
                        ->icon('heroicon-s-x-circle')
                        ->color(fn ($state) => match ($state) {
                            'pending' => 'warning',
                            "approved" => 'success',
                            "rejected" => 'danger',
                    }),
                    TextEntry::make('reason')
                    ->label('Reason'),
                    TextEntry::make('carrier.name')
                        ->label('Carrier'),
                    TextEntry::make('value')
                        ->label('Value')
                        ->money('mad'),
                    TextEntry::make('weight')
                        ->label('Weight (kg)')
                        ->suffix(' kg'),
                    TextEntry::make('isFlex')
                        ->label('Flexible')
                        ->badge()
                        ->color(fn ($state) => $state ? 'success' : 'danger')
                        ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                        TextEntry::make('description')->copyable()->limit(25)->default('No description'),
                ])->collapsible()->columns(2),
                ComponentsSection::make('Images')->schema([
                    ImageEntry::make('attachment')
                        ->label('Attachment')
                        ->size(200)

                ])->collapsible(),
            ])->columnSpan(2)

        ])->columns(3);
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
    public static function canEdit(Model $model): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRejectedShipments::route('/'),
            'create' => Pages\CreateRejectedShipment::route('/create'),
            'view' => Pages\ViewRejectedShipment::route('/{record}'),
            'edit' => Pages\EditRejectedShipment::route('/{record}/edit'),
        ];
    }
}
