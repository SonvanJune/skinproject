<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceMail;
use App\Models\Product;
use App\Models\User;
use App\Services\MailService;
use App\Services\OTPService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Mail;
use tidy;

class AdminMailController extends Controller
{
    //services
    protected MailService $mailService;
    protected UserService $userService;

    public function __construct(MailService $mailService, UserService $userService)
    {
        $this->mailService = $mailService;
        $this->userService = $userService;
    }

    /**
     * show all mail files 
     * 
     * @param Request $request
     * @return Response as view
     */
    public function index(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $mails = $this->mailService->getAllMailForms($request, $this->userService);

        return view('admin.mails.index', compact('mails'));
    }

    /**
     * show form to edit the mail file
     * 
     * @param Request $request
     * @param string $mail_id encrypted string of mail file name 
     */
    public function edit(Request $request, string $mail_id)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $getMailDTO = $this->mailService->getMailByEncryptedFileName($mail_id, $this->userService);

        if (is_string($getMailDTO)) {
            return redirect()->route('admin.mails')->with('error', $getMailDTO);
        }

        return view('admin.mails.edit', compact('getMailDTO'));
    }

    /**
     * to update new content into mail file
     * 
     * @param Request $request
     * @param string $mail_id encrypted string of mail file name
     */
    public function update(Request $request, string $mail_id)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $updateMailDTO = $this->mailService->updateMailFile($request, $mail_id, $this->userService);

        if (is_string($updateMailDTO)) {
            return back()->with('error', $updateMailDTO);
        }

        if ($updateMailDTO) {
            return back()->with('success', 'Update successfully!');
        }
        return back()->with('error', 'Cannot update mail file');
    }

    /**
     * to render the real html view of a blade mail content
     * 
     * @param Request $request
     */
    public function render(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $mail_content = $request->get('mail_content', '');

        $invoice_products = Product::limit(5)->get();

        $subtotal = 0;
        $invoice_products = collect($invoice_products)->map(function ($product) use (&$subtotal) {
            $quantity = 1;
            $product->product_quantity = $quantity;
            $subtotal += $quantity * $product->product_price;
            return $product;
        });

        $order_price = $subtotal - 230; 
        $currency = 'USD';
        $coupon = "EXAMPLECODE (-230 for Order)";
        $mail_content = preg_replace('/{{\s*\$invoice_table\s*}}/', view('component.mail.invoice_products', compact('invoice_products', 'subtotal', 'coupon', 'order_price', 'currency'))->render(), $mail_content);

        $min = (int) str_repeat("1", OTPService::OTP_LENGTH);
        $data = [
            'user' => 'Michael Jackson',
            'email' => 'john.doe@example.com',
            'phone' => '123-456-7890',
            'country' => 'USA',
            'subject' => 'Test Email',
            'order_date' => date('Y-m-d H:i:s'),
            'content' => 'I hope you’re doing well. I recently purchased a Blackpink Inspired Stage Outfit from your store (Order #A123456), but unfortunately, I need to request a refund as I received the wrong size. I originally ordered a size M, but I received a size XL instead. Please let me know the refund process and if you require me to return the incorrect item. I would appreciate your assistance in resolving this issue as soon as possible.',
            'otp' => rand($min, $min * 9) . "",
            'valid_duration' => config('valid.otp.minute.limit', OTPService::DEFAULT_VALID_OTP_MINUTE_LIMIT),
            'app_name' => config('app.name', 'SkinProject'),
            'support_mail' => config('mail.support.address', 'skinproject.support@gmail.com'),
        ];

        $fake_render = Blade::render($mail_content, $data);

        return view('layouts.mail_render', compact('fake_render'));
    }
}
