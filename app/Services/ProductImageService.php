<?php

namespace App\Services;

use App\DTOs\CreateProductImageDTO;
use App\DTOs\GetProductImage;
use App\DTOs\UpdateProductImageDTO;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Service class for managing product images.
 */
class ProductImageService
{
    public const PAGE_SIZE_DEFAULT = 1;
    public const PER_PAGE_DEFAULT = 15;

    /**
     * Creates a new product image.
     *
     * @param Request $request The HTTP request containing product image data.
     * @return mixed The created product image DTO or a string error message.
     */
    public function createProductImage(Request $request, string $post_slug)
    {
        $validator = Validator::make($request->all(), [
            'product_image_path' => 'required|string',
            'product_image_alt' => 'required|string',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        if ($request->input('product_image_path') && !file_exists(base_path($request->input('product_image_path')))) {
            return 'Image path does not exist';
        }

        if ($post_slug) {
            $product = Product::whereHas('post', function ($query) use ($post_slug) {
                $query->where('post_slug', $post_slug);
            })->first();
            if (!$product) {
                return 'Product does not exist';
            }
        }

        DB::beginTransaction();

        try {
            $productImage = new ProductImage();
            $productImage->product_image_id = (string) Str::uuid();
            $productImage->product_image_path = $request->input('product_image_path');
            $productImage->product_image_alt = $request->input('product_image_alt');
            $productImage->product_id = $product->product_id;
            $productImage->save();

            DB::commit();
            return CreateProductImageDTO::fromModel($productImage);
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to create product image: ' . $e->getMessage();
        }
    }
    /**
     * Retrieves a list of product images.
     * @param Request $request The HTTP request containing the page and per page values.
     * @return mixed The list of product images or a string error message.
     * @throws \Exception
     */
    public function createProductImageForProduct(array $data, string $product_id)
    {

        $validator = Validator::make($data, [
            'product_image_path' => 'required|string',
            'product_image_alt' => 'required|string',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        if ($data['product_image_path'] && !file_exists(base_path($data['product_image_path']))) {
            return 'Image path does not exist';
        }

        DB::beginTransaction();
        try {
            $productImage = new ProductImage();
            $productImage->product_image_id = (string) Str::uuid();
            $productImage->product_image_path = $data['product_image_path'];
            $productImage->product_image_alt = $data['product_image_alt'];
            $productImage->product_id = $product_id;
            $productImage->save();

            DB::commit();
            return CreateProductImageDTO::fromModel($productImage);
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to create product image: ' . $e->getMessage();
        }
    }

    /**
     * Updates an existing product image.
     *
     * @param Request $request The HTTP request containing updated product image data.
     * @return mixed The updated product image DTO or a string error message.
     */
    public function updateProductImage(array $data, string $product_id)
    {
        $validator = Validator::make($data, [
            'product_image_id' => 'required|string',
            'product_image_path' => 'required|string',
            'product_image_alt' => 'required|string',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $productImage = ProductImage::where('product_id', $product_id)
            ->where('product_image_id', $data['product_image_id'])
            ->first();

        if (!$productImage) {
            return 'Product image does not exist';
        }

        if ($data['product_image_path'] && !file_exists(base_path($data['product_image_path']))) {
            return 'Image path does not exist';
        }

        DB::beginTransaction();

        try {
            $productImage->product_image_path = $data['product_image_path'];
            $productImage->product_image_alt = $data['product_image_alt'];
            $productImage->product_id = $product_id;
            $productImage->save();

            DB::commit();
            return UpdateProductImageDTO::fromModel($productImage);
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to update product image: ' . $e->getMessage();
        }
    }

    /**
     * Deletes a product image.
     *
     * @param Request $request The HTTP request containing the product image ID.
     * @return string The success message or error message.
     */
    public function deleteProductImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_image_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $productImage = ProductImage::find($request->input('product_image_id'));
        if (!$productImage) {
            return 'Product image does not exist';
        }

        DB::beginTransaction();

        try {
            $productImage->delete();
            DB::commit();
            return 'Product image deleted successfully';
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to delete product image: ' . $e->getMessage();
        }
    }

    /**
     * Retrieves a list of product images.
     * @param Request $request The HTTP request containing the page and per page values.
     * @return mixed The list of product images or a string error message.
     * @throws \Exception
     */
    public function getListProductImagesByProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_slug' => 'required|string'
        ]);
        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }
        $postSlug = $request->input('post_slug');
        $product = Product::whereHas('post', function ($query) use ($postSlug) {
            $query->where('post_slug', $postSlug);
        })->first();
        if (!$product) {
            return 'Product does not exist';
        }
        $productImages = ProductImage::where('product_id', $product->product_id)->get();
        if ($productImages->isEmpty()) {
            return 'No product images found';
        }

        $productImages = $productImages->toArray();
        return GetProductImage::fromModels($productImages);
    }

    public function isUsedInProductImage($path)
    {
        $productImage = DB::table('product_images')
            ->where('product_image_path', $path)
            ->get();
        if ($productImage != null && $productImage->isNotEmpty()) {
            return true;
        } else {
            return false;
        }
    }
    public function updateProductImagePath($oldPath, $newPath)
    {
        $productImage = DB::table('product_images')
            ->where('product_image_path', $oldPath)
            ->update(['product_image_path' => $newPath]);

        return $productImage;
    }
}
