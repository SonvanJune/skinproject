<?php

namespace App\Services;

use App\DTOs\EditPaypalInforDTO;
use App\DTOs\PaypalResponseDTO;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Facades\PayPal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;


class PaypalService
{
    public const STATUS_CREATE_SUCCESS = 1;
    public const STATUS_FINISH_PAYMENT = 1;
    public const STATUS_ERR_PAYMENT = -1;
    public const STATUS_CREATE_CANCEL = 0;
    public const STATUS_CREATE_ERROR = -1;
    const FILE_PATH = 'paypal/information.php';

    /**
     * Creates a new payment from a order and processes payment via PayPal.
     * @param  Request  $request  The HTTP request containing order details like price and order_id.
     * @param  string  $user_id  The ID of the user creating the order.
     * @return PaypalResponseDTO|string  Returns a PaypalResponseDTO containing the PayPal approval link 
     * or an error message depending on the success of the process.
     */
    public function createPayment(Request $request, string $user_id)
    {
        $validator = Validator::make($request->all(), [
            'price' => 'required|string',
            'order_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        if (!$user_id || $user_id == "") {
            return "User ID is required";
        }

        $price = $request->price;
        $provider = new PayPalClient();

        $provider = PayPal::setProvider();

        $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.payment.success', ['order_id' => $request->input('order_id')]),
                "cancel_url" => route('paypal.payment.cancel', ['order_id' => $request->input('order_id')]),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $price
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return PaypalResponseDTO::fromModel($links['href'], self::STATUS_CREATE_SUCCESS);
                }
            }
            return PaypalResponseDTO::fromModel('paypal.payment.cancel', self::STATUS_CREATE_CANCEL);
        } else {
            return PaypalResponseDTO::fromModel('paypal.payment.error', self::STATUS_CREATE_ERROR);
        }
    }

    /**
     * Finish the payment status and updates the order status accordingly.
     * @param  Request  $request  The HTTP request containing the PayPal payment details.
     * @return PaypalResponseDTO|string  Returns a success message or an error message depending on the payment status.
     */
    public function finishPayment(Request $request, string $user_id, OrderService $orderService)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $provider = new PayPalClient();

        $provider = PayPal::setProvider();

        $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $request->merge(['order_status' => OrderService::STATUS_BOUGHT]);
            $orderService->changeOrderStatus($request, $user_id);
            return PaypalResponseDTO::fromModel('checkout', self::STATUS_FINISH_PAYMENT, 'Transaction complete. We have sent the products to your email');
        } else {
            return PaypalResponseDTO::fromModel('checkout', self::STATUS_ERR_PAYMENT, $response['message'] ?? 'Something went wrong.');
        }
    }

    /**
     * Edit paypal information.
     */
    public function saveCredentials(Request $request, UserService $userService)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => [
                'required',
                'string',
                'regex:/^[A-Za-z0-9\-_]{13,}$/',
            ],
            'client_secret' => [
                'required',
                'string',
                'regex:/^[A-Za-z0-9\-_]{32,}$/',
            ],
            'app_id' => [
                'required',
                'string',
                'regex:/^[A-Za-z0-9\-_]{13,}$/',
            ]
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $data = [
            'client_id' => $userService->encrypt_with_key($request->input('client_id'), UserService::DEFAULT_ENCRYPT_KEY),
            'client_secret' => $userService->encrypt_with_key($request->input('client_secret'), UserService::DEFAULT_ENCRYPT_KEY),
            'app_id' => $userService->encrypt_with_key($request->input('app_id'), UserService::DEFAULT_ENCRYPT_KEY),
        ];

        $content = "<?php\n\nreturn [\n";
        foreach ($data as $key => $val) {
            $val = addslashes($val);
            $content .= "    '{$key}' => '{$val}',\n";
        }
        $content .= "];\n";
        File::put(resource_path(self::FILE_PATH), $content);
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate(resource_path(self::FILE_PATH), true);
        }
        return EditPaypalInforDTO::fromModel('PayPal information saved successfully!');
    }

    /**
     * Get paypal information.
     */
    public function getCredential(UserService $userService)
    {
        $path = str_replace('\\', '/', resource_path(self::FILE_PATH));
        $data = include $path;
        $clientId = $userService->decrypt_with_key($data['client_id'], UserService::DEFAULT_ENCRYPT_KEY);
        $clientSecret = $userService->decrypt_with_key($data['client_secret'], UserService::DEFAULT_ENCRYPT_KEY);
        $appId = $userService->decrypt_with_key($data['app_id'], UserService::DEFAULT_ENCRYPT_KEY);
        return [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'app_id' => $appId,
        ];
    }
}
