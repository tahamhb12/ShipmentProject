<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css.css">
    <title>Document</title>
</head>
<body>
    <div class="invoice">
        <div class="sender_info">
            <h1>Shipment</h1>
            <p>Sender:</p>
            <p>{{$sender}}</p>
        </div>
        <hr>
        <div class="receiver_info">
            <h3>{{$receiver}}</h3>
            <h3>{{$street_address}}</h3>
            <h3>{{$city}}</h3>
            <h3>{{$state}} {{$postal_code}}</h3>
            <h3>{{$country}}</h3>
        </div>
        <hr>
        <div class="shipment_info">
            <div class="order_no">
                <p>Order No:</p>
                <p>{{$tracking_number}}</p>
            </div>
            <div class="order_no">
                <p>Reference:</p>
                <p>34234234</p>
            </div>
            <div class="order_no">
                <p>Weight:</p>
                <p>{{$weight}}KG</p>
            </div>
        </div>
        <hr>
        {{-- <p>{!! $barcode !!}</p> --}}
        <div class="barcode">
            <p class="test">{!! $barcode !!}</p>
            <p>Delivery Instruction: Please leave with reception</p>
        </div>
    </div>
</body>
</html>
