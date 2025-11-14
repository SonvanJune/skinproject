<table
    style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px; margin-top: 10px;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">No.</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Product Name</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">Quantity</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Unit Price</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Discount Price</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Total ({{ $currency }})</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($invoice_products as $index => $product)
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $product->product_name }}</td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{ $product->product_quantity }}
                </td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">
                    {{ $product->product_price }}
                </td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">
                    {{ $product->product_price_sale ?? "No sale" }}
                </td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">
                    {{ $product->product_price_sale ?? $product->product_price}}</td>
            </tr>
        @endforeach

        <tr>
            <td colspan="5" style="border: 1px solid #ddd; padding: 8px; text-align: right; font-weight: bold;">
                Subtotal</td>
            <td style="border: 1px solid #ddd; padding: 8px; text-align: right; font-weight: bold;">{{ $subtotal }}
            </td>
        </tr>

        <tr>
            <td colspan="5" style="border: 1px solid #ddd; padding: 8px; text-align: right; font-weight: bold;">
                Coupon</td>
            <td style="border: 1px solid #ddd; padding: 8px; text-align: right; font-weight: bold;">
                {{ $coupon ?? "No coupon" }}
            </td>
        </tr>

        <tr>
            <td colspan="5" style="border: 1px solid #ddd; padding: 8px; text-align: right; font-weight: bold;">Total
                Amount</td>
            <td style="border: 1px solid #ddd; padding: 8px; text-align: right; font-weight: bold;">
                {{ $order_price }}
            </td>
        </tr>
    </tbody>
</table>
