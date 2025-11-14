<?php

namespace App\Services;

use App\DTOs\CreatePostDTO;
use App\DTOs\PaginatedDTO;
use App\DTOs\PostAdminPageDTO;
use App\DTOs\PostPageDTO;
use App\DTOs\UpdatePostDTO;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


/**
 * Service class for managing posts.
 *
 * This class provides methods for creating, updating, deleting, and retrieving posts.
 */
class PostService
{
    public const TYPE_ADMIN = 0;
    public const TYPE_USER = 1;

    /**
     * Create a new post.
     *
     * @param Request $request The request containing post data.
     * @param string $user_id The ID of the user creating the post.
     * @return CreatePostDTO|string The created post DTO or an error message.
     */
    public function createPost(Request $request, string $user_id)
    {
        if (!$user_id) {
            return 'User not found';
        }

        $validator = Validator::make($request->all(), [
            'post_name' => 'nullable|string|max:255',
            'post_slug' => 'required|string|max:255',
            'post_release' => 'nullable|date',
            'post_content' => 'required|string',
            'post_image_path' => 'required|string|max:255',
            'post_image_alt' => 'required|string|max:255',
            'post_status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $validate = $this->validatePostProperties($request, null);
        if ($validate) {
            return $validate;
        }


        if (!User::where('user_id', $user_id)->exists()) {
            return 'User not found';
        }

        DB::beginTransaction();
        try {
            $post = new Post();

            $post->post_id = Str::uuid()->toString();
            $post->post_name = $request->input('post_name');
            $post->post_slug = $request->input('post_slug');
            $post->post_release = $request->input('post_release');
            $post->post_status = $request->input('post_status') ?? Post::STATUS_EXPIRE;
            $post->post_type = Post::TYPE_POST;
            $post->user_id = $user_id;
            $post->post_content = $request->input('post_content');
            $post->post_image_path = $request->input('post_image_path');
            $post->post_image_alt = $request->input('post_image_alt');

            $post->save();

            DB::commit();

            return CreatePostDTO::fromModel($post);
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to create post: ' . $e->getMessage();
        }
    }

    /**
     * Create a new product description post.
     *
     * @param Request $request The request containing post data.
     * @param string $user_id The ID of the user creating the post.
     * @return string The ID of the created post or an error message.
     */
    public function createProductDescription(Request $request, string $user_id)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'post_slug' => 'required|string|max:255',
            'post_content' => 'required|string',
            'post_image_path' => 'required|string|max:255',
            'post_image_alt' => 'required|string|max:255',
            'product_status' => 'nullable|string',
            'product_release' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        if (!User::where('user_id', $user_id)->exists()) {
            return 'User not found';
        }

        DB::beginTransaction();
        try {
            $post = new Post();

            $post->post_id = Str::uuid()->toString();
            $post->post_name = $request->input('product_name');
            $post->post_slug = $request->input('post_slug');
            $post->post_release = $request->input('product_release');
            $post->post_status = $request->input('product_status') ?? Post::STATUS_EXPIRE;
            $post->post_type = Post::TYPE_PRODUCT;
            $post->user_id = $user_id;
            $post->post_content = $request->input('post_content');
            $post->post_image_path = $request->input('post_image_path');
            $post->post_image_alt = $request->input('post_image_alt');

            $post->save();
            DB::commit();

            return $post->post_id;
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to create post: ' . $e->getMessage();
        }
    }

    /**
     * Update an existing post.
     *
     * @param Request $request The request containing post data.
     * @return UpdatePostDTO|string The updated post DTO or an error message.
     */
    public function updatePost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|uuid',
            'post_name' => 'nullable|string|max:255',
            'post_slug' => 'required|string|max:255',
            'post_release' => 'nullable|date',
            'post_content' => 'required|string',
            'post_image_path' => 'required|string|max:255',
            'post_image_alt' => 'required|string|max:255',
            'post_status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $post = Post::find($request->input('post_id'));
        if (!$post) {
            return 'Post not found';
        }

        $validate = $this->validatePostProperties($request, $post);
        if ($validate) {
            return $validate;
        }

        DB::beginTransaction();
        try {
            $post->post_name = $request->input('post_name');
            $post->post_slug = $request->input('post_slug');
            $post->post_release = $request->input('post_release');
            $post->post_status = $request->input('post_status') ?? Post::STATUS_EXPIRE;
            $post->post_content = $request->input('post_content');
            $post->post_image_path = $request->input('post_image_path');
            $post->post_image_alt = $request->input('post_image_alt');
            $post->updated_at = $request->input('updated_at');
            $post->save();

            DB::commit();

            return UpdatePostDTO::fromModel($post);
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to update post: ' . $e->getMessage();
        }
    }

    /**
     * Update an existing product description post.
     *
     * @param Request $request The request containing post data.
     * @return string The ID of the updated post or an error message.
     */
    public function updateProductDescription(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'post_id' => 'required|uuid',
            'product_name' => 'required|string|max:255',
            'post_slug' => 'required|string|max:255',
            'post_content' => 'required|string',
            'post_image_path' => 'required|string|max:255',
            'post_image_alt' => 'required|string|max:255',
            'product_status' => 'nullable|string',
            'product_release' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $post = Post::find($request->input('post_id'));
        if (!$post) {
            return 'Post not found';
        }

        DB::beginTransaction();
        try {
            $post->post_name = $request->input('product_name');
            $post->post_slug = $request->input('post_slug');
            $post->post_release = $request->input('product_release');
            $post->post_status = $request->input('product_status') ?? Post::STATUS_EXPIRE;
            $post->post_content = $request->input('post_content');
            $post->post_image_path = $request->input('post_image_path');
            $post->post_image_alt = $request->input('post_image_alt');
            $post->save();

            DB::commit();

            return $post;
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to update post: ' . $e->getMessage();
        }
    }

    /**
     * Change the status or release date of a post or product description.
     *
     * @param Request $request The request containing post data.
     * @return string The ID of the updated post or an error message.
     */
    public function changeStatusOrReleaseDateOfPostOrProductDecription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|uuid',
            'post_release' => 'nullable|date',
            'post_status' => 'nullable|boolean',
            'post_type' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        if (!$request->input("post_release") && !$request->input("post_status")) {
            return 'Both post_release and post_status cannot be null';
        }

        $post = Post::find($request->input('post_id'));
        if (!$post) {
            return 'Post not found';
        }

        if ($request->input('post_release') && $request->input('post_release') < now()) {
            return 'Release date is invalid!';
        }

        if ($request->input("post_type") == Post::TYPE_PRODUCT && !$post->product()->exists()) {
            return 'Post does not have a product';
        }

        DB::beginTransaction();
        try {
            $post->post_release = $request->input('post_release') ? $request->input('post_release') : $post->post_release;
            $post->post_status = $request->input('post_status') ?? $post->post_status;
            $post->save();

            DB::commit();

            return $post->post_id;
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to update post: ' . $e->getMessage();
        }
    }

    /**
     * Delete a post by setting its type to 'deleted'.
     *
     * @param Request $request The request containing post ID.
     * @return bool|string True if the post was deleted, or an error message.
     */
    public function deletePost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $post = Post::find($request->input('post_id'));
        if (!$post) {
            return 'Post not found';
        }

        DB::beginTransaction();
        try {
            $post->post_status = Post::TYPE_POST_DELETE;
            $post->save();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to delete post: ' . $e->getMessage();
        }
    }

    /**
     * Get a post by its slug.
     *
     * @param Request $request The request containing post slug.
     * @return PostPageDTO|string The post DTO or an error message.
     */
    public function getPostBySlug(Request $request, UserService $userService)
    {
        $validator = Validator::make($request->all(), [
            'post_slug' => 'required|string'
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $post = Post::where('post_release', '<=', now())
            ->where('post_status', Post::STATUS_RELEASE)
            ->where('post_type', '!=', Post::TYPE_POST_DELETE)
            ->where('post_slug', $request->input('post_slug'))
            ->first();

        if (!$post || $post->post_type == Post::TYPE_PRODUCT) {
            return 'Post not found';
        }

        return PostPageDTO::fromModel($post, $userService);
    }

    /**
     * Get a post by its slug.
     *
     * @param Request $request The request containing post slug.
     * @return PostPageDTO|string The post DTO or an error message.
     */
    public function getRawPostBySlug(Request $request, UserService $userService)
    {
        $validator = Validator::make($request->all(), [
            'post_slug' => 'required|string'
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $post = Post::where('post_slug', $request->input('post_slug'))->
        where('post_status', '!=', Post::TYPE_POST_DELETE)->where('post_type', '!=', Post::TYPE_PRODUCT)->first();

        if (!$post) {
            return 'Post not found';
        }

        return PostAdminPageDTO::fromModel($post, $userService);
    }

    /**
     * Get a paginated list of posts.
     *
     * @param Request $request The request containing pagination parameters.
     * @param int $type The type list of posts.
     * @return PaginatedDTO|string The paginated DTO or an error message.
     */
    public function getListPostsPerPage(Request $request, int $type, UserService $userService)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|numeric|integer',
            'per_page' => 'nullable|numeric|integer'
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $perPage = $request->input('per_page', Post::PER_PAGE);
        $page = $request->input('page', 1);
        $skip = ($page - 1) * $perPage;

        $posts = Post::where('post_status', '!=', Post::TYPE_POST_DELETE)
            ->where('post_type', '!=', Post::TYPE_PRODUCT);


        if ($type == self::TYPE_USER) {
            $posts = $posts->where('post_release', '<=', now())
                ->where('post_status', Post::STATUS_RELEASE);
        }

        $posts = $posts->skip($skip)

            ->take($perPage)
            ->get();

        if ($posts->isEmpty()) {
            return 'Posts not found';
        }

        $total = Post::where('post_release', '<=', now())
            ->where('post_status', Post::STATUS_RELEASE)
            ->where('post_type', '!=', Post::TYPE_POST_DELETE)
            ->where('post_type', '!=', Post::TYPE_PRODUCT)
            ->count();

        return PaginatedDTO::fromData(PostPageDTO::fromListModels($posts, $userService), $page, $perPage, $total);
    }

    /**
     * Get a paginated list of posts.
     *
     * @param Request $request The request containing pagination parameters.
     * @param int $type The type list of posts.
     * @return PaginatedDTO|string The paginated DTO or an error message.
     */
    public function getAllPostsPerPage(Request $request, int $type, UserService $userService)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|numeric|integer',
            'per_page' => 'nullable|numeric|integer',
            'key' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $perPage = $request->input('per_page', Post::PER_PAGE);
        $page = $request->input('page', 1);
        $skip = ($page - 1) * $perPage;

        $posts = Post::where('post_status', '!=', Post::TYPE_POST_DELETE)->where('post_type', '!=', Post::TYPE_PRODUCT);
        if ($type == self::TYPE_USER) {
            $posts = $posts->where('post_release', '<=', now())
                ->where('post_status', Post::STATUS_RELEASE);
        }

        $key = $request->has('key') ? $request->input('key') : "";
        if ($key) {
            $posts = $posts
                ->where('post_name', 'LIKE', '%' . $key . '%')
                ->skip($skip)
                ->take($perPage);
        }

        $total = count($posts->get());

        $posts = $posts
            ->skip($skip)
            ->take($perPage)
            ->get();

        return PaginatedDTO::fromData(PostAdminPageDTO::fromListModels($posts, $userService), $page, $perPage, $total, $key ?? "");
    }

    /**
     * Search a paginated list of posts.
     *
     * @param Request $request The request containing pagination parameters.
     * @return PaginatedDTO|string The paginated DTO or an error message.
     */
    public function searchListPostsPerPage(Request $request, UserService $userService)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|numeric|integer',
            'per_page' => 'nullable|numeric|integer',
            'search' => 'required|string'
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $perPage = $request->input('per_page', Post::PER_PAGE);
        $page = $request->input('page', 1);
        $skip = ($page - 1) * $perPage;

        $posts = Post::where('post_type', '!=', Post::TYPE_POST_DELETE)
            ->where('post_type', '!=', Post::TYPE_PRODUCT)
            ->where('post_release', '<=', now())
            ->where('post_status', Post::STATUS_RELEASE)
            ->where('post_name', 'like', `$` . $request->input('search') . `$`)
            ->skip($skip)
            ->take($perPage)
            ->get();

        if ($posts->isEmpty()) {
            return 'Posts not found';
        }

        $total = Post::where('post_release', '<=', now())
            ->where('post_status', Post::STATUS_RELEASE)
            ->where('post_type', '!=', Post::TYPE_POST_DELETE)
            ->count();

        return PaginatedDTO::fromData(PostPageDTO::fromListModels($posts, $userService), $page, $perPage, $total);
    }

    /**
     * Validate the properties of the post before processing.
     *
     * This function performs the following checks:
     * - Checks if the post slug is unique.
     * - Verifies if the specified post image exists in the public directory.
     * - Validates the post release date (if provided) to ensure it is not in the past.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing post data.
     * @return string|null A validation error message if validation fails, or null if validation passes.
     */
    private function validatePostProperties(Request $request, ?Post $post)
    {
        if ($post) {
            if (Post::where('post_slug', $request->input('post_slug'))->where('post_id', '!=', $post->post_id)->exists()) {
                return 'Post slug is already taken!';
            }
        } else {
            if (Post::where('post_slug', $request->input('post_slug'))->exists()) {
                return 'Post slug is already taken!';
            }
        }


        $imagePath = base_path($request->input('post_image_path'));
        if (!file_exists($imagePath)) {
            return 'Post thumbnail not found!';
        }

        if (!$post) {
            $postReleaseDate = $request->input('post_release');
            if ($postReleaseDate < now()) {
                return 'Release date is invalid!';
            }
        }

        return null;
    }

    public function updatePostImagePath($oldImageFilePath, $newImageFilePath)
    {
        $post = DB::table('posts')->where('post_image_path', $oldImageFilePath)
            ->update(['post_image_path' => $newImageFilePath]);
        return $post;
    }
    public function isFileUsedInPost($imageFilePath)
    {
        $product = DB::table('posts')
            ->where('post_file_path', $$imageFilePath)
            ->get();
        if ($product != null && $product->isNotEmpty()) {
            return true;
        } else {
            return false;
        }
    }
}
