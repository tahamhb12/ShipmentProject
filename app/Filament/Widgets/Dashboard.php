<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use App\Models\Shipment;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Dashboard extends BaseWidget
{
    protected function getStats(): array
    {
        $approved_shipments = Shipment::where('status','approved')->count();
        $pending_shipments = Shipment::where('status','pending')->count();
        $rejected_shipments = Shipment::where('status','rejected')->count();
        $total_payments = Payment::count();
        $total_paid = Payment::all()->sum('amount');
        $total_shipment_cost = Shipment::where('status','approved')->sum('shipment_price');
        return [
            Stat::make('Pending Shipments',$pending_shipments)
            ->description('Total number of pending shipments')
            ->descriptionIcon('heroicon-s-truck',IconPosition::Before)
            ->chart([1,20,10,10,20,40])
            ->color('primary'),
            Stat::make('Approved Shipments',$approved_shipments)
            ->description('Total number of approved shipments')
            ->descriptionIcon('heroicon-s-truck',IconPosition::Before)
            ->chart([1,20,10,10,20,40])
            ->color('success'),
            Stat::make('Rejected Shipments',$rejected_shipments)
            ->description('Total number of rejected shipments')
            ->descriptionIcon('heroicon-o-truck',IconPosition::Before)
            ->chart([1,20,10,10,20,40])
            ->color('danger'),
            Stat::make('Total Payments',$total_payments)
            ->description('Total number of payments done')
            ->descriptionIcon('heroicon-m-currency-dollar',IconPosition::Before)
            ->chart([1,20,10,10,20,40])
            ->color('primary'),
            Stat::make('Total Paid',number_format($total_paid,0) .' DH')
            ->description('Total amount paid')
            ->descriptionIcon('heroicon-m-currency-dollar',IconPosition::Before)
            ->chart([1,20,10,10,20,40])
            ->color('success'),
            Stat::make('Total Unpaid',number_format($total_shipment_cost-$total_paid,0) .' DH')
            ->description('Total amount unpaid')
            ->descriptionIcon('heroicon-m-currency-dollar',IconPosition::Before)
            ->chart([1,20,10,10,20,40])
            ->color('danger')
        ];
    }
    protected function getColumns(): int
    {
        return 3;
    }
}
