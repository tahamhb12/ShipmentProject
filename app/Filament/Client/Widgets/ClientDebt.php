<?php

namespace App\Filament\Client\Widgets;

use App\Models\Payment;
use App\Models\Shipment;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ClientDebt extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user()->id;
        $user_shipment_prices = Shipment::where('user_id',$user)->where('status','approved')->get()->sum('shipment_price');
        $user_payment_amount = Payment::where('client_id',$user)->get()->sum('amount');
        $user_shipments = Shipment::where('user_id',$user)->where('status','approved')->count();
        return [
            Stat::make('My Debts',$user_shipment_prices-$user_payment_amount.' DH')
            ->description('How much the company owe you')
            ->descriptionIcon('heroicon-m-currency-dollar',IconPosition::Before)
            ->chart([1,3,5,10,20,40])
            ->color('danger'),
            Stat::make('My Shipments',$user_shipments)
            ->description('Shipments Done')
            ->descriptionIcon('heroicon-m-currency-dollar',IconPosition::Before)
            ->chart([1,3,5,10,20,40])
            ->color('success')
        ];
    }
}
