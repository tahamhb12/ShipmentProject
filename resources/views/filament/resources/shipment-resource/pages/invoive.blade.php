<x-filament-panels::page>
    <div class="invoice" style="max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; font-family: Arial, sans-serif; background: #fff;">
        <!-- Sender Info -->
        <div class="sender_info" style="text-align: center; margin-bottom: 20px;">
            <h1 style="margin: 0;">LOGO</h1>
            <p style="margin: 5px 0;"><strong>Sender:</strong></p>
            <p style="margin: 0;">{{ $shipment->user->name }}</p>
        </div>
        <hr style="border: 1px solid #ddd;">

        <!-- Receiver Info -->
        <div class="receiver_info" style="margin-bottom: 20px;">
            <p style="font-weight: bold; margin: 5px 0;">Receiver:</p>
            <h3 style="margin: 5px 0;">{{ $shipment->receiver->name }}</h3>
            <h3 style="margin: 5px 0;">{{ $shipment->street_address }}</h3>
            <h3 style="margin: 5px 0;">{{ $shipment->city }}</h3>
            <h3 style="margin: 5px 0;">{{ $shipment->state }} {{ $shipment->postal_code }}</h3>
            <h3 style="margin: 5px 0;">{{ $shipment->country }}</h3>
        </div>
        <hr style="border: 1px solid #ddd;">

        <!-- Shipment Info -->
        <div class="shipment_info" style="margin-bottom: 20px;">
            <div class="order_no" style="display: flex; justify-content: space-between; padding: 5px 0;">
                <p style="font-weight: bold;">Order No:</p>
                <p>{{ $shipment->tracking_number }}</p>
            </div>
            <div class="order_no" style="display: flex; justify-content: space-between; padding: 5px 0;">
                <p style="font-weight: bold;">Reference:</p>
                <p>34234234</p>
            </div>
            <div class="order_no" style="display: flex; justify-content: space-between; padding: 5px 0;">
                <p style="font-weight: bold;">Weight:</p>
                <p>{{ $shipment->weight }} KG</p>
            </div>
        </div>
        <hr style="border: 1px solid #ddd;">

        <!-- Barcode -->
        <div class="barcode" style="text-align: center; margin-top: 20px;">
            <p>{!! $codebar !!}</p>
        </div>
    </div>
</x-filament-panels::page>
