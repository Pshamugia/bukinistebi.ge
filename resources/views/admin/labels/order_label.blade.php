<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 18px;
            margin: 0;
            padding: 20px;
        }
        .box { border: 1px solid #000; padding: 10px; }
        .barcode { margin-top: 20px; text-align:center; }
    </style>
</head>
<body>

<div class="box">
    <strong>გამგზავნი:</strong><br>
Bukinistebi.ge <br>
<br>

    <strong>მიმღები:</strong> <br>
    {{ $order->name }}<br><br>
    {{ $order->city }}, {{ $order->address }}<br>
@php
    // Normalize the phone number
    $phone = preg_replace('/\D+/', '', $order->phone); // remove non-digits

    // Remove country code if present (995 OR +995)
    if (strlen($phone) === 12 && substr($phone, 0, 3) == '995') {
        $phone = substr($phone, 3); // keep only 9 digits
    }

    // Format only if valid 9-digit Georgian mobile number
    if (strlen($phone) === 9) {
        $formatted = preg_replace(
            '/^(\d{3})(\d{2})(\d{2})(\d{2})$/',
            '$1-$2-$3-$4',
            $phone
        );
    } else {
        $formatted = $order->phone; // fallback
    }
@endphp

<strong>ტელ:</strong> {{ $formatted }}<br><br>

<strong>ღირებულება:</strong> {{ number_format($order->total, 2, '.', '') + 0 }} GEL
</div>

<div class="barcode">
    <div style="display:inline-block;">
        {!! DNS1D::getBarcodeHTML($order->id, 'EAN13', 2,60) !!}
    </div>
</div>


</body>
</html>
