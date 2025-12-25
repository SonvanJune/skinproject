<?php

namespace App\Services;

use App\DTOs\GetProductDTO;
use App\DTOs\GetUserDTO;
use App\Mail\InvoiceMail;
use App\Models\Order;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PdfService
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Create invoice PDF snapshot
     *
     * @return true|string
     */
    public function createInvoiceSnapshot(
        array $data,
        User $user,
        UserService $userService
    ) {
        $validator = Validator::make(
            $data,
            [
                'order_id' => 'required|string',
                'country'  => 'nullable|max:255',
                'currency' => 'nullable|max:255',
            ]
        );

        $currency = "USD";

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $order = Order::where('order_id', $data['order_id'])->first();
        if (!$order) {
            return 'Order not found.';
        }

        $cart = $order->cart;
        $products = $order->cart->products()->get();
        $invoice_products = GetProductDTO::fromModels($products, GetUserDTO::fromModel($user));

        $subtotal = 0;
        foreach ($invoice_products as $product) {
            $product->product_quantity = 1;
            $subtotal += $product->product_quantity * ($product->product_price_sale ?? $product->product_price);
        }
        $vat_detail = $order->vat_detail;
        $vat_value = $order->vat_value;
        $coupon = $this->getCouponOfOrder($order);
        $order_price = $order->order_price;

        $pdfData = [
            'paymentTime' => $order->created_at->format('Y/m/d H:i:s'),
            'invoice_products' => $invoice_products,
            'name' => $user->user_first_name . " " . $user->user_last_name,
            'email' => $user->user_email,
            'phone' => $user->user_phone,
            'subtotal' => $subtotal,
            'vat_detail' => $vat_detail,
            'vat_value' => $vat_value,
            'coupon' => $coupon,
            'order_price' => $order_price,
            'currency' => $currency
        ];

        $pdf = Pdf::loadView('pdf.invoice', $pdfData);

        $fileName = 'invoice_' . $order->order_id . '.pdf';
        $fullPath = public_path('payment-snapshot/' . $fileName);

        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        file_put_contents($fullPath, $pdf->output());

        return true;
    }

    public function getCouponOfOrder(Order $order): string
    {
        $coupon = "";
        if ($order->coupon) {
            if ($order->coupon->product) {
                if ($order->coupon->coupon_price) {
                    $coupon = $order->coupon->coupon_code . ' (-' . $order->coupon->coupon_price . '$ for ' . $order->coupon->product->product_name . ')';
                } else {
                    $coupon = $order->coupon->coupon_code . ' (-' . $order->coupon->coupon_per_hundred . '$ for ' . $order->coupon->product->product_name . ')';
                }
            } else {
                if ($order->coupon->coupon_price) {
                    $coupon = $order->coupon->coupon_code . ' (-' . $order->coupon->coupon_price . '$ for Order)';
                } else {
                    $coupon = $order->coupon->coupon_code . ' (-' . $order->coupon->coupon_per_hundred . '% for Order)';
                }
            }
        }
        return $coupon;
    }
}
