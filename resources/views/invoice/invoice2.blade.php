<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            margin: 0;
            padding: 5px;
            font-size: 30px;
        }
        .invoice {
            max-width: 800px; /* Increased width */
            margin: auto;
            padding: 20px; /* Increased padding */
            border: 1px solid #ddd;
            background: #fff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px; /* Increased cell padding */
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .barcode {
            text-align: center;
            margin-top: 20px;
            transform: translateX(-50%);
            left: 50%;
            position:relative;
        }
    </style>
</head>
<body>
    <div class="invoice">
        <h1 style="text-align: center;">LOGO</h1>

        <!-- Sender Info -->
        <table>
            <tr>
                <th colspan="2">Sender Information</th>
            </tr>
            <tr>
                <td><strong>Sender:</strong></td>
                <td>{{ $sender }}</td>
            </tr>
        </table>

        <hr>

        <!-- Receiver Info -->
        <table>
            <tr>
                <th colspan="2">Receiver Information</th>
            </tr>
            <tr>
                <td width='160px'><strong>Receiver:</strong></td>
                <td>{{ $receiver }}</td>
            </tr>
            <tr>
                <td><strong>Address:</strong></td>
                <td>{{ $street_address }}, {{ $city }}, {{ $state }} {{ $postal_code }}, {{ $country }}</td>
            </tr>
        </table>

        <hr>

        <!-- Shipment Info -->
        <table>
            <tr>
                <th colspan="2">Shipment Information</th>
            </tr>
            <tr>
                <td width='15px'><strong>Order No:</strong></td>
                <td>{{ $tracking_number }}</td>
            </tr>
            <tr>
                <td><strong>Reference:</strong></td>
                <td>34234234</td>
            </tr>
            <tr>
                <td><strong>Weight:</strong></td>
                <td>{{ $weight }} KG</td>
            </tr>
        </table>

        <hr>

        <!-- Barcode -->

        <div class="barcode"  >
            {!! $barcode !!}
        </div>

    </div>
</body>
</html>
