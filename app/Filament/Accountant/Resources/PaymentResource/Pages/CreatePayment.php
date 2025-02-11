<?php

namespace App\Filament\Accountant\Resources\PaymentResource\Pages;

use App\Filament\Accountant\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\Shipment;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    public function beforeCreate(){
        $amount = $this->form->getState()['amount'];
        $user_id = $this->form->getState()['client_id'];
        $user_shipment_prices = Shipment::where('user_id',$user_id)->where('status','approved')->get()->sum('shipment_price');
        $user_payment_amount = Payment::where('client_id',$user_id)->get()->sum('amount');
        $user_debt = $user_shipment_prices-$user_payment_amount;
        if((int)$amount>$user_debt){
            Notification::make()
                ->title("amount must be less than $user_debt")
                ->danger()
                ->send();
            $this->halt();
        }
    }

}
