<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
            margin: 20px;
        }

        h2 {
            margin: 0;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        th {
            background: #f5f5f5;
            text-align: center;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .no-border td {
            border: none;
        }

        .summary td {
            border: none;
            padding: 5px;
        }

        .summary .label {
            text-align: right;
            font-weight: bold;
        }

        .summary .value {
            text-align: right;
        }

        .total {
            font-weight: bold;
            border-top: 1px solid #000;
        }

        /* 🔥 QUAN TRỌNG cho dompdf */
        table, tr, td {
            page-break-inside: avoid;
        }

        img {
            max-height: 60px;
        }

        .header-table td {
            border: none;
        }

        .footer {
            margin-top: 30px;
            font-size: 11px;
            text-align: center;
            color: #777;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td>
                <img src="{{ public_path('images/logo.png') }}">
            </td>
            <td style="text-align:right">
                <h2>Invoice Detail</h2>
            </td>
        </tr>
    </table>

    <br>

    <!-- Info -->
    <table class="no-border">
        <tr><td><strong>Full name:</strong> {{ $name }}</td></tr>
        <tr><td><strong>Phone:</strong> {{ $phone }}</td></tr>
        <tr><td><strong>Email:</strong> {{ $email }}</td></tr>
        <tr><td><strong>Payment Time:</strong> {{ $paymentTime }}</td></tr>
    </table>

    <br>

    <!-- Product Table -->
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Product Name</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Discount</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice_products as $index => $product)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $product->product_name }}</td>
                <td class="text-center">{{ $product->product_quantity }}</td>
                <td class="text-right">${{ $product->product_price }}</td>
                <td class="text-right">
                    {{ $product->product_price_sale ? '$'.$product->product_price_sale : 'No sale' }}
                </td>
                <td class="text-right">
                    ${{ $product->product_price_sale ?? $product->product_price }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <br>

    <!-- Summary -->
    <table class="summary">
        <tr>
            <td class="label">Subtotal</td>
            <td class="value">${{ $subtotal }}</td>
        </tr>
        <tr>
            <td class="label">Coupon</td>
            <td class="value">{{ $coupon ?? 'No coupon' }}</td>
        </tr>
        <tr>
            <td class="label">VAT ({{ $vat_detail }})</td>
            <td class="value">${{ $vat_value }}</td>
        </tr>
        <tr>
            <td class="label total">Total</td>
            <td class="value total">${{ $order_price }}</td>
        </tr>
    </table>

    <div class="footer">
        This invoice was generated automatically at the time of payment.
    </div>

</body>
</html>