<?php

namespace App\Mail;

use App\DTOs\GetProductDTO;
use App\DTOs\GetUserDTO;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;

class InvoiceMail extends Mailable
{
    public string $user;
    public string $email;
    public string $phone;
    public string $country;

    public string $app_name;
    public string $support_mail;

    public string $order_date;
    public array $invoice_products;
    public float $order_price;
    public string $currency;
    public string $coupon;
    public string $vat_detail;
    public string $vat_value;

    public string $path;
    public string $file_name;

    /**
     * Create a new message instance.
     * 
     * @param User $user the client
     * @param string $country the client's country
     * @param Order $order client's order
     * @param string $currency the currency
     * @param string $path the path from base directory of the attached file
     * @param string $file_name the new file name to send to client
     * @return void
     */
    public function __construct(User $user, string $path , string $file_name,Order $order, string $file_password, string $currency = "USD", string $country = "Unknown",)
    {
        $this->user = $user->user_first_name . " " . $user->user_last_name;
        $this->email = $user->user_email;
        $this->phone = $user->user_phone;
        $this->country = $country;
        $this->path = $path;
        $this->file_name = $file_name;

        $this->app_name = config('app.name', __('message.webName'));
        $this->support_mail = config('mail.email_customer', '');

        $this->order_date = $order->created_at->format('Y/m/d H:i:s');
        $products = $order->cart->products()->get();
        $this->invoice_products = GetProductDTO::fromModels($products, GetUserDTO::fromModel($user));
        $this->order_price = $order->order_price;
        $this->vat_detail = $order->vat_detail;
        $this->vat_value = $order->vat_value;
        $this->currency = $currency;
        $this->coupon = $this->getCouponOfOrder($order);
    }

    /**
     * Build the message contains the otp to change user's password
     *
     * @return $this the instance to send the message
     */
    public function build()
    {
        $mail_content = file_get_contents(resource_path('views/mail/invoice.blade.php'));

        $invoice_products = $this->invoice_products;
        $order_price = $this->order_price;
        $currency = $this->currency;
        $subtotal = 0;
        foreach ($invoice_products as $product) {
            $product->product_quantity = 1;
            $subtotal += $product->product_quantity * ($product->product_price_sale ?? $product->product_price);
        }
        $coupon = $this->coupon;
        $vat_detail = $this->vat_detail;
        $vat_value = $this->vat_value;

        $mail_content = preg_replace('/{{\s*\$invoice_table\s*}}/', view('component.mail.invoice_products', compact('invoice_products', 'subtotal','vat_detail','vat_value', 'coupon', 'order_price', 'currency'))->render(), $mail_content);

        $data = [
            'user' => $this->user,
            'email' => $this->email,
            'phone' => $this->phone,
            'country' => $this->country,
            'order_date' => $this->order_date,
            'app_name' => $this->app_name,
            'support_mail' => $this->support_mail,
        ];
        $html_render = Blade::render($mail_content, $data);

        return $this
            ->to($this->email)
            ->subject("SkinProject Invoice – Order #[" . $this->order_date . "]")
            ->attach(
                $this->path,
                [
                    'as' => $this->file_name,
                    'mime' => 'application/zip'
                ]
            )
            ->html($html_render);
    }

    public function getCouponOfOrder(Order $order): string
    {
        $coupon = "";
        if ($order->coupon) {
            if ($order->coupon->product) {
                if ($order->coupon->coupon_price) {
                    $coupon = $order->coupon->coupon_code . ' (-' . $order->coupon->coupon_price . '$ for ' . $order->coupon->product->product_name . ')';
                } 
                else {
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
