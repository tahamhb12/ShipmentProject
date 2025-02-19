<?php

namespace App\Filament\Client\Widgets;

use App\Models\Payment;
use App\Models\Shipment;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class Dashbaord extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user()->id;
        $total_shipment_cost = Shipment::where('user_id',$user)
        ->where('status','approved')
        ->sum('shipment_price');
        $total_payments = Payment::where('client_id',$user)
        ->sum('amount');
        $user_approved_shipments = Shipment::where('user_id',$user)
        ->where('status','approved')
        ->count();
        $user_pending_shipments = Shipment::where('user_id',$user)
        ->where('status','pending')
        ->count();
        $user_rejected_shipments = Shipment::where('user_id',$user)
        ->where('status','rejected')
        ->count();
        $outstandingBalance = $total_shipment_cost - $total_payments;

        return [
            $outstandingBalance<0?
            Stat::make('Credit Amount',number_format($outstandingBalance*-1,0) .' DH')
            ->description('Total amount the company owes you')
            ->descriptionIcon('heroicon-m-currency-dollar',IconPosition::Before)
            ->chart([1,20,10,10,20,40])
            ->color('success')
            :
            Stat::make('Due Amount',number_format($outstandingBalance,0).' DH')
            ->description('Total amount due to the company')
            ->descriptionIcon('heroicon-m-currency-dollar',IconPosition::Before)
            ->chart([1,20,10,10,20,40])
            ->color('danger')
            ,
            Stat::make('Total Shipment Cost',number_format($total_shipment_cost,0).' DH')
            ->description('Total cost of approved shipments')
            ->descriptionIcon('heroicon-m-currency-dollar',IconPosition::Before)
            ->chart([1,20,10,10,20,40])
            ->color('success'),
            Stat::make('Approved Shipments',$user_approved_shipments)
            ->description('Total number of approved shipments')
            ->descriptionIcon('heroicon-s-truck',IconPosition::Before)
            ->chart([1,20,10,10,20,40])
            ->color('info'),
            Stat::make('Pending Shipments',$user_pending_shipments)
            ->description('Total number of pending shipments')
            ->descriptionIcon('heroicon-s-truck',IconPosition::Before)
            ->chart([1,20,10,10,20,40])
            ->color('primary')
        ];
    }
    protected function getColumns(): int
    {
        return 2;
    }
}
