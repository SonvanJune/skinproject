<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailFinishPayment;
use App\Services\CouponService;
use App\Services\MailService;
use App\Services\OrderService;
use App\Services\PaypalService;
use App\Services\PdfService;
use App\Services\UserService;
use Illuminate\Http\Request;

class PayPalController extends Controller
{
    protected $orderService;
    protected $userService;
    protected $couponService;
    protected $paypalService;
    protected $mailService;
    protected $pdfService;
    public const TYPE_COUPON_PRODUCT = 'couponProduct';

    public function __construct(OrderService $orderService, UserService $userService, CouponService $couponService, PaypalService $paypalService, MailService $mailService, PdfService $pdfService)
    {
        $this->orderService = $orderService;
        $this->userService = $userService;
        $this->couponService = $couponService;
        $this->paypalService = $paypalService;
        $this->mailService = $mailService;
        $this->pdfService = $pdfService;
    }

    public function payment(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService, true);
        if (parent::checkMaintenance($user) == "off") {
            return redirect()->route('maintenance');
        }
        if ($request->price && $user) {
            $order = $this->orderService->createOrder($request, $user->user_id);
            $request->merge(['order_id' => $order->order_id]);
            $addOrderCoupon = 0;
            if ($request->input('type_coupon') != self::TYPE_COUPON_PRODUCT) {
                $addOrderCoupon = $this->couponService->attachCouponOrder($request, $order->order_id);
            }
            if (!parent::checkIsString($order) && !parent::checkIsString($addOrderCoupon)) {
                $createPayment = $this->paypalService->createPayment($request, $user->user_id);
                if (parent::checkIsString($createPayment)) {
                    return redirect()
                        ->route('checkout')
                        ->with('error', $createPayment);
                }
                switch ($createPayment->status) {
                    case PaypalService::STATUS_CREATE_SUCCESS:
                        return redirect()->away($createPayment->link);
                    case PaypalService::STATUS_CREATE_CANCEL:
                        return redirect()->route($createPayment->link);
                    default:
                        return redirect()->route($createPayment->link);
                }
            } else {
                return redirect()
                    ->route('checkout')
                    ->with('error', $order);
            }
        } else {
            return redirect()
                ->route('paypal.payment.error');
        }
    }

    public function paymentCancel(Request $request)
    {
        $this->orderService->deleteOrderById($request);
        return redirect()
            ->route('checkout')
            ->with('cancel', 'You have canceled the transaction.');
    }

    public function paymentError(Request $request)
    {
        $this->orderService->deleteOrderById($request);
        return redirect()
            ->route('checkout')
            ->with('error', 'Something went wrong.');
    }

    public function paymentSuccess(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService, true, false);
        if (parent::checkMaintenance($user) == "off") {
            return redirect()->route('maintenance');
        }
        if ($request->query('order_id')) {
            $request->merge(['order_id' => $request->query('order_id')]);
            $finishPayment = $this->paypalService->finishPayment($request, $user->user_id, $this->orderService);
            if (parent::checkIsString($finishPayment)) {
                return redirect()
                    ->route('checkout')
                    ->with('error', $finishPayment);
            }
            switch ($finishPayment->status) {
                case PaypalService::STATUS_FINISH_PAYMENT:
                    $invoicePath = $this->pdfService->createInvoiceSnapshot($request->all(), $user, $this->userService);
                    if ($invoicePath) {
                        $this->mailService->sendEmailFinishPayment($request->all(), $user, $this->userService, $invoicePath);
                    } else {
                        return redirect()->route($finishPayment->link)->with('warning', "We couldn’t send the email at this time. Please try again later.
                                In the meantime, you can download your product anytime from your Order History.
                                ");
                    }
                    return redirect()->route($finishPayment->link)->with('success', $finishPayment->message);
                default:
                    return redirect()->route($finishPayment->link)->with('error', $finishPayment->message);
            }
        }
    }
}
