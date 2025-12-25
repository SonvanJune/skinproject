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
            line-height: 1.6;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info {
            margin-bottom: 20px;
        }

        .info p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
            border: 1px solid #ddd;
            padding: 8px;
        }

        td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: middle;
        }

        td.text-center {
            text-align: center;
        }

        td.text-right {
            text-align: right;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        .summary {
            width: 100%;
            margin-top: 20px;
        }

        .summary td {
            border: none;
            padding: 6px 8px;
        }

        .summary .label {
            text-align: right;
            font-weight: bold;
            width: 80%;
        }

        .summary .value {
            text-align: right;
            width: 20%;
        }

        .total {
            font-size: 14px;
            font-weight: bold;
            border-top: 2px solid #333;
            padding-top: 8px;
        }

        .footer {
            margin-top: 40px;
            font-size: 11px;
            text-align: center;
            color: #777;
        }
    </style>
</head>

<body>

    <h2>Invoice Detail</h2>

    <div class="info">
        <p><strong>Full name:</strong> {{ $name }}</p>
        <p><strong>Phone:</strong> {{ $phone }}</p>
        <p><strong>Email:</strong> {{ $email }}</p>
        <p><strong>Payment Time:</strong> {{ $paymentTime }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Discount Price</th>
                <th>Total (USD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice_products as $index => $product)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td class="text-center">{{ $product->product_quantity }}</td>
                    <td class="text-right">${{ $product->product_price }}</td>
                    <td class="text-right">${{ $product->product_price_sale ?? "No sale"}}</td>
                    <td class="text-right">${{ $product->product_price_sale ?? $product->product_price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="summary">
        <tr>
            <td class="label">Total Products Price</td>
            <td class="value">${{ $subtotal }}</td>
        </tr>
        <tr>
            <td class="label">Coupon</td>
            <td class="value">{{ $coupon ?? "No coupon" }}</td>
        </tr>
        <tr>
            <td class="label">VAT({{$vat_detail}})</td>
            <td class="value">${{ $vat_value }}</td>
        </tr>
        <tr>
            <td class="label total">Total Amount</td>
            <td class="value total">${{ $order_price }}</td>
        </tr>
    </table>

    <div class="footer">
        This invoice was generated automatically at the time of payment.
    </div>

</body>

</html>
