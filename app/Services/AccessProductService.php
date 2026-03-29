<?php

namespace App\Services;

use App\DTOs\GetProductDTO;
use App\DTOs\GetUserDTO;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class AccessProductService
{
    const MAX_PRODUCTS = 5;

    /**
     * Get a product by its slug
     *
     * @param Request $request The request containing product slug
     * @param int $role The role of the user (admin or regular user)
     * @return void
     */

    public function addProduct(Request $request): void
    {
        $validator = Validator::make($request->all(), [
            'user_id'   => 'required|string|',
            'post_slug' => 'required|string|',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $userId   = $request->input('user_id');
        $product = Product::whereHas('post', function ($query) use ($request) {
            $query->where('post_slug', $request->input('post_slug'))
                ->where('post_release', '<=', now())
                ->where('post_status', Post::STATUS_RELEASE);
        })->first();

        $productId = $product->product_id;

        DB::transaction(function () use ($userId, $productId) {

            $exists = DB::table('access_products')
                ->where('user_id', $userId)
                ->where('product_id', $productId)
                ->exists();

            if ($exists) {
                if ($exists) {
                    DB::table('access_products')
                        ->where('user_id', $userId)
                        ->where('product_id', $productId)
                        ->update([
                            'created_at' => now()
                        ]);
                    return;
                }
            }

            $count = DB::table('access_products')
                ->where('user_id', $userId)
                ->count();

            if ($count >= self::MAX_PRODUCTS) {
                $oldest = DB::table('access_products')
                    ->where('user_id', $userId)
                    ->orderBy('created_at', 'asc')
                    ->first();

                if ($oldest) {
                    DB::table('access_products')
                        ->where('user_id', $oldest->user_id)
                        ->where('product_id', $oldest->product_id)
                        ->delete();
                }
            }

            DB::table('access_products')->insert([
                'user_id'    => $userId,
                'product_id' => $productId,
                'created_at' => Carbon::now()
            ]);
        });
    }

    public function readUserAccessProducts(Request $request, ?GetUserDTO $user): array
    {
        $userModel = User::find($user->user_id);

        $list = $userModel->accessedProducts()
            ->orderByPivot('created_at', 'desc')
            ->get();

        return GetProductDTO::fromModels($list, $user);
    }
}
