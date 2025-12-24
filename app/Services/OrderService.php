<?php

namespace App\Services;

use App\DTOs\ChangeStatusOrderDTO;
use App\DTOs\CreateOrderDTO;
use App\DTOs\DeleteOrderDTO;
use App\DTOs\GetOrderAdminDTO;
use App\DTOs\GetOrderDTO;
use App\DTOs\PaginatedDTO;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Service class for managing orders.
 */
class OrderService
{
    /**
     * Order status representing a deleted order.
     *
     * @var int
     */
    public const STATUS_DELETED = -1;

    /**
     * Order status representing an inactive order.
     *
     * @var int
     */
    public const STATUS_INACTIVE = 0;

    /**
     * Order status representing an order waiting for payment.
     *
     * @var int
     */
    public const STATUS_WAIT_PAY = 1;

    /**
     * Order status representing a purchased order.
     *
     * @var int
     */
    public const STATUS_BOUGHT = 2;

    /**
     * Order payment method representing PayPal.
     *
     * @var int
     */
    public const PAYMENT_METHOD_PAYPAL = 1;

    /**
     * Default page number for pagination.
     *
     * @var int
     */
    public const PAGE_SIZE_DEFAULT = 1;

    /**
     * Default number of items per page for pagination.
     *
     * @var int
     */
    public const PER_PAGE_DEFAULT = 6;

    /**
     * Creates a new order from a cart.
     *
     * @param  Request  $request  The HTTP request containing order details.
     * @param  string  $userId  The ID of the user creating the order.
     * @return CreateOrderDTO|string  Returns a CreateOrderDTO if successful, otherwise an error message.
     */
    public function createOrder(Request $request, string $userId): CreateOrderDTO|string
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required|uuid',
            'order_payment' => 'required|numeric',
            'coupon_id' => 'nullable|string',
            'price' => 'required|string',
            'vat_detail' => 'required|string',
            'vat_value' => 'required|string'
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $cartId = $request->input('cart_id');

        $user = User::find($userId);

        if (! $user) {
            return 'User not found.';
        }

        $cart = Cart::with('products')->find($cartId);

        if (! $cart) {
            return 'Cart not found.';
        }

        if ($cart->user_id != $userId) {
            return 'User does not have permission to create an order from this cart.';
        }

        if ($cart->products->isEmpty()) {
            return 'Cannot create an order from an empty cart.';
        }

        if ($cart->cart_status == self::STATUS_BOUGHT) {
            return 'Cannot create an order from a cart that has already been purchased.';
        }

        if ($cart->cart_status == self::STATUS_DELETED) {
            return 'Cannot create an order from a deleted cart.';
        }

        if (Order::where('cart_id', $cartId)->exists()) {
            return 'An order already exists for this cart.';
        }

        DB::beginTransaction();

        try {
            $order = new Order();
            $order->order_id = (string) Str::uuid();
            $order->cart_id = $cartId;
            $order->order_payment = $request->input('order_payment');
            $order->order_price = $request->input('price');
            $order->order_status = self::STATUS_WAIT_PAY;
            $order->coupon_id = $request->input('coupon_id') ? $request->input('coupon_id') : null;
            $order->vat_detail = $request->input('vat_detail');
            $order->vat_value = $request->input('vat_value');

            $order->save();

            DB::commit();

            return CreateOrderDTO::fromModel($order, $userId);
        } catch (Exception $e) {
            DB::rollBack();

            return 'Failed to create order: ' . $e->getMessage();
        }
    }

    /**
     * Changes the status of an order.
     *
     * @param  Request  $request  The HTTP request containing the order ID and new status.
     * @param  string  $userId  The ID of the user making the request.
     * @return ChangeStatusOrderDTO|string  Returns true if the status is updated successfully, otherwise an error message.
     */
    public function changeOrderStatus(Request $request, string $userId): ChangeStatusOrderDTO|string
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|uuid',
            'order_status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $orderId = $request->input('order_id');
        $newStatus = $request->input('order_status');

        $order = Order::where('order_id', $orderId)->first();

        if (! $order) {
            return 'Order not found.';
        }

        $cart = Cart::find($order->cart_id);

        if (!$cart) {
            return 'Cart not found.';
        }

        if ($cart->user_id != $userId) {
            return 'User does not have permission to modify this order.';
        }

        DB::beginTransaction();

        try {
            $flag = DB::update('update orders set order_status = ? where order_id = ?', [$newStatus, $orderId]);
            if ($flag && $newStatus == self::STATUS_BOUGHT) {
                DB::update('update carts set cart_status = ? where cart_id = ?', [CartService::STATUS_BOUGHT, $cart->cart_id]);
            }

            DB::commit();

            if (! $flag) {
                return 'Failed to update order status.';
            }
            $order->order_status = $newStatus;

            return true;
        } catch (Exception $e) {
            DB::rollBack();

            return 'Failed to update order status: ' . $e->getMessage();
        }
    }

    /**
     * Retrieves a list of orders for a specific user with pagination.
     *
     * @param  Request  $request  The HTTP request containing pagination parameters.
     * @param  string  $userId  The ID of the user to retrieve orders for.
     * @return PaginatedDTO|string  Returns a PaginatedDTO containing orders and pagination data if successful, otherwise an error message.
     */
    public function getOrdersByUser(Request $request, string $userId): PaginatedDTO|string
    {
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer',
            'per_page' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $perPage = $request->input('per_page', self::PER_PAGE_DEFAULT);
        $page = $request->input('page', self::PAGE_SIZE_DEFAULT);
        $skip = ($page - 1) * $perPage;

        $user = User::find($userId);

        if (!$user) {
            return 'User not found.';
        }

        $orders = Order::whereHas('cart', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with('cart.products')
            ->get();

        if ($orders->isEmpty()) {
            return 'No orders found for this user.';
        }
        $total = $orders->count();
        $orders = $orders
            ->skip($skip)
            ->take($perPage);
        return PaginatedDTO::fromData(
            GetOrderDTO::fromModels($orders, $userId),
            $page,
            $perPage,
            $total
        );
    }

    /**
     * Retrieves an order by its ID.
     *
     * @param  Request  $request  The HTTP request containing the order ID.
     * @return GetOrderDTO|string  Returns a GetOrderDTO if the order is found, otherwise an error message.
     */
    public function getOrderById(Request $request): GetOrderDTO|string
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $orderId = $request->input('order_id');

        $order = Order::where('order_id', $orderId)->first();

        if (!$order) {
            return 'Order not found.';
        }

        return GetOrderDTO::fromModel($order, $order->cart->user_id);
    }

    /**
     * Retrieves an order by its ID.
     *
     * @param  Request  $request  The HTTP request containing the order ID.
     * @return DeleteOrderDTO|string  Returns a GetOrderDTO if the order is found, otherwise an error message.
     */
    public function deleteOrderById(Request $request): DeleteOrderDTO|string
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|uuid',
        ]);
        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $order = Order::where('order_id', $request->input('order_id'))->first();
        if (!$order) {
            return 'Order not found.';
        }

        DB::beginTransaction();
        try {
            Order::where('order_id', $order->order_id)->delete();
            DB::commit();
            return DeleteOrderDTO::fromModel('Order deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to delete coupon: ' . $e->getMessage();
        }
    }

    /**
     * Retrieves all orders by its ID.
     *
     * @param  Request  $request  The HTTP request containing the order ID.
     * @return DeleteOrderDTO|string  Returns a GetOrderDTO if the order is found, otherwise an error message.
     */
    public static function deleteOrderByUser(Request $request): DeleteOrderDTO|string
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|uuid',
        ]);
        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $user = User::where('user_id', $request->input('user_id'))->first();
        if (!$user) {
            return 'User not found.';
        }

        DB::beginTransaction();
        try {
            foreach ($user->carts as $cart) {
                Order::where('cart_id', $cart->cart_id)->where('order_status', '=', self::STATUS_WAIT_PAY)->delete();
            }
            DB::commit();
            return DeleteOrderDTO::fromModel('Order deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to delete coupon: ' . $e->getMessage();
        }
    }

    /**
     * Retrieves a list of orders for a specific user with pagination.
     *
     * @param  Request  $request  The HTTP request containing pagination parameters.
     * @param  string  $userId  The ID of the user to retrieve orders for.
     * @return PaginatedDTO|string  Returns a PaginatedDTO containing orders and pagination data if successful, otherwise an error message.
     */
    public function getOrdersByAdmin(Request $request, UserService $userService): array|string
    {
        $validator = Validator::make($request->all(), [
            'startDate' => 'nullable|string',
            'endDate' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $startDate = Carbon::parse($request->query('startDate'));
        $endDate = Carbon::parse($request->query('endDate'));

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])->get();

        if ($orders->isEmpty()) {
            return 'No orders found for this time';
        }

        return GetOrderAdminDTO::fromModels($orders, $userService);
    }
}
