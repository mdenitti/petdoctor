<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bill for {{ $patient->name }}</title>
</head>
<body>
    <h1>Bill for {{ $patient->name }}</h1>
    <p>Dear {{ $patient->owner->name }},</p>
    <p>Here is the bill for your pet <strong>{{ $patient->name }}</strong>:</p>
    <table style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th style="border:1px solid #000; padding:5px; text-align:left;">Description</th>
                <th style="border:1px solid #000; padding:5px; text-align:right;">Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($treatments as $treatment)
                <tr>
                    <td style="border:1px solid #000; padding:5px;">{{ $treatment->description }}</td>
                    <td style="border:1px solid #000; padding:5px; text-align:right;">€{{ number_format($treatment->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @php $total = $treatments->sum('price'); @endphp
    <p><strong>Total: €{{ number_format($total, 2) }}</strong></p>
    <p>Thank you for your business.</p>
</body>
</html>
